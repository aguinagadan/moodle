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
 * Edwiser RemUI Homepage Builder
 * @package    local_remuihomepage
 * @copyright  (c) 2019 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

class local_remuihomepage_plugin {

    /**
     * Renderer object
     * @var Object
     */
    private $output = null;

    /**
     * Get renderer for plugin
     * @return Object Plugin renderer object
     */
    public function get_renderer() {
        if ($this->output == null) {
            global $PAGE;
            $this->output = $PAGE->get_renderer('local_remuihomepage');
        }
        return $this->output;
    }

    /**
     * Get type of plugin
     * @return String Plugin type
     */
    public function get_type() {
        return 'homepage';
    }

    /**
     * Get unique name of plugin
     *
     * @return String Plugin unique name
     */
    public function get_name() {
        return 'homepage';
    }

    /**
     * Get THEME_THEME_COMPONENT of plugin
     *
     * @return String Plugin THEME_THEME_COMPONENT
     */
    public function get_component() {
        return 'local_remuihomepage';
    }

    /**
     * Add admin settings for the plugin
     *
     * @param Object $page Admin page object
     */
    public function admin_settings($page) {
        global $CFG;
        if (class_exists('admin_setting_description')) {
            $page->add(new admin_setting_description(
                'newhomepagedescription',
                '',
                get_string('newhomepagedescription', 'theme_remui')
            ));
            $page->add(new admin_setting_description('newhomepagedescription1', '', ''));
        }
        $migrator = new \local_remuihomepage\frontpage\migrator();
        if ($migrator->has_settings()) {
            $page->add(new local_remuihomepage_admin_setting_migrate(
                get_string('migrate', 'local_remuihomepage'), // Button title.
                $CFG->wwwroot . '/?redirect=0&migrate=1', // Target url.
                get_string('migratedesc', 'local_remuihomepage') // Description.
            ));
        }
    }

    public function layout($templatecontext) {
        global $USER, $PAGE, $CFG, $SITE;

        // Purge plugin cache to regenerate section from db data.
        // This is added to support multi-lang.
        $configlang = get_config('local_remuihomepage', 'processed_lang');
        if (optional_param('lang', '', PARAM_ALPHA) != '' || $configlang != current_language()) {
            $cache = cache::make('local_remuihomepage', 'frontpage');
            $cache->purge();
        }

        $class = "latest-frontpage full-sidebar";
        if (isset($templatecontext["remui_lite"])) {
            $class .= " remui_lite ";
        }
        $templatecontext['bodyattributes'] = str_replace(
            "class=\"", "class=\"" . $class,
            $templatecontext['bodyattributes']
        );
        $sm = new \local_remuihomepage\frontpage\section_manager();
        if (isloggedin()) {
            $usercontext = context_user::instance($USER->id);
            $templatecontext['caneditfrontpage'] = has_capability('local/remuihomepage:editfrontpage', $usercontext);

            // Migrate previous settings.
            if ($templatecontext['caneditfrontpage'] && optional_param('migrate', false, PARAM_BOOL)) {
                $migrator = new \local_remuihomepage\frontpage\migrator();
                $migrator->create_sections_from_older_configs();
                $sm->reset_cache();
                redirect(new moodle_url('', array('redirect' => 0)));
            }

            if ($templatecontext['caneditfrontpage'] && optional_param('frontpageediting', false, PARAM_BOOL)) {
                $USER->editing = optional_param('editpage', false, PARAM_BOOL);
                if (optional_param('publish', false, PARAM_BOOL)) {
                    $sm->publish_sections();
                    $sm->reset_cache();
                }
                redirect(new moodle_url('', array('redirect' => 0)));
            }
            $templatecontext['userisediting'] = $templatecontext['caneditfrontpage'] && $PAGE->user_is_editing();
            if ($templatecontext['userisediting']) {
                $sm->create_draft_configs();
            }
            if ($templatecontext['caneditfrontpage']) {
                $templatecontext['customizepagevalue'] = !$templatecontext['userisediting'];
                $templatecontext['sesskey'] = sesskey();
                $templatecontext['customizepagelabel'] = get_string(
                    $templatecontext['userisediting'] ? 'turneditingoff' : 'turneditingon'
                );
            }
        }
        $orderconfigname = 'sections_order';
        if (isset($templatecontext['userisediting'])) {
            $orderconfigname = 'draft_sections_order';
        }
        $order = get_config('theme_remui', $orderconfigname);
        $transparentheader = get_config('theme_remui', 'frontpagetransparentheader');
        if ($transparentheader == 1) {
            $templatecontext['transparentheader'] = $transparentheader;
            $templatecontext['headercolor'] = get_config('theme_remui', 'frontpageheadercolor');
        }
        $PAGE->requires->data_for_js('transparentheader', $transparentheader);
        if ($order) {
            $instances = explode(',', substr($order, 1, -1));
            if ($instances[0] != null) {
                $configdata = $sm->get_config_by_instanceid($instances[0]);
                if ($configdata != null
                    && $configdata->name == 'slider'
                    && $transparentheader
                    && ($configdata->visible == true || isset($USER->editing))) {
                    $templatecontext['bodyattributes'] = str_replace(
                        "class=\"", "class=\"has-slider animate-header ",
                        $templatecontext['bodyattributes']
                    );
                }
            }
        }
        if (get_config('theme_remui', 'frontpageloader')) {
            $templatecontext['loader'] = \theme_remui\toolbox::setting_file_url('frontpageloader', 'frontpageloader');
        }

        // Appear animation configuration for js.
        $PAGE->requires->data_for_js('appearanimation', get_config('theme_remui', 'frontpageappearanimation'));
        if (!$style = get_config('theme_remui', 'frontpageappearanimationstyle')) {
            $style = 'animation-slide-bottom';
        }
        $PAGE->requires->data_for_js('appearanimationstyle', $style);

        // Check for default sections.
        if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
            $frontpagelayout = $CFG->frontpageloggedin;
        } else {
            $frontpagelayout = $CFG->frontpage;
        }

        if ($frontpagelayout != "") {
            $templatecontext['hasdefaultsections'] = true;
        }

        // Check for site summary.
        $modinfo = get_fast_modinfo($SITE);
        $section = $modinfo->get_section_info(1);

        if (!empty($section->summary)) {
            $templatecontext['hasdefaultsections'] = true;
        }

        if ($PAGE->user_is_editing()) {
            $templatecontext['hasdefaultsections'] = true;
        }
        $PAGE->requires->strings_for_js([
            'homepagesettings',
        ], 'theme_remui');
        $PAGE->requires->strings_for_js([
            'sectionupdated',
            'sectiondeleted',
            'slidersection',
            'aboutussection',
            'contactsection',
            'featuresection',
            'coursessection',
            'teamsection',
            'testimonialsection',
            'htmlsection',
            'separatorsection',
            'addsection',
            'homepagesettings'
        ], 'local_remuihomepage');
        $PAGE->requires->strings_for_js(['success'], 'moodle');
        $templatecontext['disablesidebarpinning'] = true;
        $templatecontext['pin_aside'] = false;
        $templatecontext['bodyattributes'] = str_replace("pinaside", "", $templatecontext['bodyattributes']);
        unset($templatecontext['sitecolor']);
        $templatecontext['customhomepage'] = $this->get_renderer()->render_from_template(
            'local_remuihomepage/layout',
            $templatecontext
        );
        $templatecontext['floating_buttons'] = $this->get_renderer()->render_from_template(
            'local_remuihomepage/floating_buttons',
            $templatecontext
        );
        if ($CFG->branch == 35) {
            $templatecontext['bodyattributes'] = str_replace(
                "class=\"", "class=\"v35-frontpage ",
                $templatecontext['bodyattributes']
            );
        }
        // -------------------------------------------------.
        // Remui Lite Fixes.
        $templatecontext['bodyattributes'] = str_replace("sidebar-pinned", "", $templatecontext['bodyattributes']);
        $templatecontext['bodyattributes'] = str_replace("sidebar-open", "", $templatecontext['bodyattributes']);
        $templatecontext['bodyattributes'] = str_replace("hasblocks", "", $templatecontext['bodyattributes']);
        // -------------------------------------------------
        return $templatecontext;
    }
}
