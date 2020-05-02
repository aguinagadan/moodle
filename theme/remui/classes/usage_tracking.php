<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Edwiser Usage Tracking
 * We send anonymous user data to imporve our product compatibility with various plugins and systems.
 * 
 * Moodle's new Bootstrap theme engine
 * @package    theme_remui
 * @copyright  (c) 2018 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui;

class usage_tracking {
    
    /**
     * Send usage analytics to Edwiser, only anonymous data is sent.
     * 
     * every 7 days the data is sent, function runs for admin user only
     */
    public function send_usage_analytics() {

        global $DB, $CFG;

        
        // execute code only if current user is site admin
        // reduces calls to DB
        if (is_siteadmin()) {

            // check consent to send tracking data
            $consent = get_config('theme_remui', 'enableusagetracking');

            if($consent) {

                // TODO: A check needs to be added here, that user has agreed to send this data.
                // TODO: We will have to add a settings checkbox for that or something similar.

                $last_sent_data = isset($CFG->usage_data_last_sent_theme_remui)?$CFG->usage_data_last_sent_theme_remui:false;

                // if current time is greater then saved time, send data again
                if(!$last_sent_data || time() > $last_sent_data) {
                    $result_arr = [];
                    $analytics_data = json_encode($this->prepare_usage_analytics());

                    $url = "https://edwiser.org/wp-json/edwiser_customizations/send_usage_data";
                    // call api endpoint with data
                    $ch = curl_init();

                    //set the url, number of POST vars, POST data
                    curl_setopt($ch,CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $analytics_data);                                                                  
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                        'Content-Type: application/json',                                                                                
                        'Content-Length: ' . strlen($analytics_data))                                                                       
                    );

                    //execute post
                    $result = curl_exec($ch);
                    if($result) {
                        $result_arr = json_decode($result, 1);
                    }
                    //close connection
                    curl_close($ch);

                    // save new timestamp, 7 days --- save only if api returned success
                    if(isset($result_arr['success']) && $result_arr['success']) {
                        set_config('usage_data_last_sent_theme_remui', time()+604800);
                    }
                }
            }
        }
    }

     /** 
      * Prepare usage analytics 
      */
    private function prepare_usage_analytics() {

        global $CFG, $DB;

        // Suppressing all the errors here, just in case the setting does not exists, to avoid many if statements
        $analytics_data = array(
            'siteurl' => preg_replace('#^https?://#', '', rtrim(@$CFG->wwwroot,'/')), // replace protocol and trailing slash
            'product_name' => "Edwiser RemUI",
            'product_settings' => $this->get_plugin_settings('theme_remui'), // all settings in json, of current product which you are tracking,
            'active_theme' => @$CFG->theme,
            'total_courses' => $DB->count_records('course'), // including hidden courses
            'total_categories' => $DB->count_records('course_categories'), // includes hidden categories
            'total_users' => $DB->count_records('user', array('deleted' => 0)), // exclude deleted
            'installed_plugins' => $this->get_user_installed_plugins(), // along with versions
            'system_version' => @$CFG->release, // Moodle version
            'system_lang' => @$CFG->lang,
            'system_settings' => array(
                'blog_active' => @$CFG->enableblogs,
                'cachejs_active' => @$CFG->cachejs,
                'messaging_active' => @$CFG->messaging,
                'theme_designermode_active' => @$CFG->themedesignermode,
                'multilang_filter_active' => @$CFG->filter_multilang_converted,
                'moodle_debug_mode' => @$CFG->debug,
                'moodle_debug_debugdisplay' => @$CFG->debugdisplay,
                'moodle_memory_limit' => @$CFG->extramemorylimit,
                'moodle_maxexec_time_limit' => @$CFG->maxtimelimit,
                'moodle_curlcache_ttl' => @$CFG->curlcache,
            ),
            'server_os' => @$CFG->os,
            'server_ip' => @$_SERVER['REMOTE_ADDR'],
            'web_server' => @$_SERVER['SERVER_SOFTWARE'],
            'databasename' => @$CFG->dbtype,
            'php_version' => phpversion(),
            'php_settings' => array(
                'memory_limit' => ini_get("memory_limit"),
                'max_execution_time' => ini_get("max_execution_time"),
                'post_max_size' => ini_get("post_max_size"),
                'upload_max_filesize' => ini_get("upload_max_filesize"),
                'memory_limit' => ini_get("memory_limit")
            ),
        );

        return $analytics_data;
    }

    // get plugins installed by user excluding the default plugins
    private function get_user_installed_plugins() {
        // all plugins - "external/installed by user"
        $all_plugins = array();

        $pluginman = \core_plugin_manager::instance();
        $plugininfos = $pluginman->get_plugins();
        
        foreach($plugininfos as $key => $modtype) {
            foreach($modtype as $key => $plug) {
                if (!$plug->is_standard() && !$plug->is_subplugin()) {
                    // each plugin data, // can be different structuer in case of wordpress product
                    $all_plugins[] = array(
                        'name' => $plug->displayname,
                        'versiondisk' => $plug->versiondisk,
                        'versiondb' => $plug->versiondb,
                        'versiondisk' => $plug->versiondisk,
                        'release' => $plug->release
                    );
                }
            }
        }

        return $all_plugins;
    }

    // get specific settings of the current plugin, eg: remui
    private function get_plugin_settings($plugin) {
        // get complete config
        $plugin_config = get_config($plugin);
        $filtered_plugin_config = array();

        // Suppressing all the errors here, just in case the setting does not exists, to avoid many if statements
        $filtered_plugin_config['enableannouncement'] = @$plugin_config->enableannouncement;
        $filtered_plugin_config['announcementtype'] = @$plugin_config->announcementtype;
        $filtered_plugin_config['enablerecentcourses'] = @$plugin_config->enablerecentcourses;
        $filtered_plugin_config['enableheaderbuttons'] = @$plugin_config->enableheaderbuttons;
        $filtered_plugin_config['mergemessagingsidebar'] = @$plugin_config->mergemessagingsidebar;
        $filtered_plugin_config['courseperpage'] = @$plugin_config->courseperpage;
        $filtered_plugin_config['courseanimation'] = @$plugin_config->courseanimation;
        $filtered_plugin_config['enablenewcoursecards'] = @$plugin_config->enablenewcoursecards;
        $filtered_plugin_config['activitynextpreviousbutton'] = @$plugin_config->activitynextpreviousbutton;
        $filtered_plugin_config['logoorsitename'] = @$plugin_config->logoorsitename;
        $filtered_plugin_config['fontselect'] = @$plugin_config->fontselect;
        $filtered_plugin_config['fontname'] = @$plugin_config->fontname;
        $filtered_plugin_config['customcss'] = isset($plugin_config->customcss)?base64_encode($plugin_config->customcss):''; // encode to avoid any issues with special chars in css
        $filtered_plugin_config['enablecoursestats'] = @$plugin_config->enablecoursestats;
        $filtered_plugin_config['enabledictionary'] = @$plugin_config->enabledictionary;
        $filtered_plugin_config['courseeditbutton'] = @$plugin_config->courseeditbutton;
        $filtered_plugin_config['poweredbyedwiser'] = @$plugin_config->poweredbyedwiser;
        $filtered_plugin_config['navlogin_popup'] = @$plugin_config->navlogin_popup;
        $filtered_plugin_config['loginsettingpic'] = isset($plugin_config->loginsettingpic)?1:0;
        $filtered_plugin_config['brandlogopos'] = @$plugin_config->brandlogopos;

        $homepageinstalled = \core_plugin_manager::instance()->get_plugin_info('local_remuihomepage');
        $filtered_plugin_config['new_homepage_installed'] = 0;
        if($homepageinstalled != null) {
            $filtered_plugin_config['new_homepage_installed'] = 1;
        }
        $filtered_plugin_config['new_homepage_active'] = @$plugin_config->frontpagechooser;

        $dashboard_blocks_installed = \core_plugin_manager::instance()->get_plugin_info('block_remuiblck');
        $filtered_plugin_config['dashboard_blocks_installed'] = 0;
        if($dashboard_blocks_installed != null) {
            $filtered_plugin_config['dashboard_blocks_installed'] = 1;
        }
    
        return $filtered_plugin_config;
    }

}
