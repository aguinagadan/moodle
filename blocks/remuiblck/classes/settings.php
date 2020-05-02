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
 * Edwiser RemUI
 * @package    theme_remui
 * @copyright  (c) 2018 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_remuiblck_settings {
    public static function add_settings(&$settings) {
        // Dashboard settings
        $page = new admin_settingpage('theme_remui_dashboard', get_string('dashboardsetting', 'block_remuiblck'));

        $page->add(new admin_setting_heading(
            'theme_remui_upsection',
            get_string('dashboardsetting', 'block_remuiblck'),
            format_text(get_string('dashboardsettingdesc', 'block_remuiblck'), FORMAT_MARKDOWN)
        ));

        // Course Progress Block Setting
        $name = 'theme_remui/enablecourseprogressblock';
        $title = get_string('courseprogressblock', 'block_remuiblck');
        $description = get_string('courseprogressblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enableenrolledusersblock';
        $title = get_string('enrolledusersblock', 'block_remuiblck');
        $description = get_string('enrolledusersblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enablequizattemptsblock';
        $title = get_string('quizattemptsblock', 'block_remuiblck');
        $description = get_string('quizattemptsblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enablecourseanlyticsblock';
        $title = get_string('courseanlyticsblock', 'block_remuiblck');
        $description = get_string('courseanlyticsblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enablelatestmembersblock';
        $title = get_string('latestmembersblock', 'block_remuiblck');
        $description = get_string('latestmembersblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enableaddnotesblock';
        $title = get_string('addnotesblock', 'block_remuiblck');
        $description = get_string('addnotesblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enablerecentfeedbackblock';
        $title = get_string('recentfeedbackblock', 'block_remuiblck');
        $description = get_string('recentfeedbackblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enablerecentforumsblock';
        $title = get_string('recentforumsblock', 'block_remuiblck');
        $description = get_string('recentforumsblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enablemanagecoursesblock';
        $title = get_string('managecoursesblock', 'block_remuiblck');
        $description = get_string('managecoursesblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enablescheduletaskblock';
        $title = get_string('scheduletaskblock', 'block_remuiblck');
        $description = get_string('scheduletaskblockdesc', 'block_remuiblck');
        $default = true;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Must add the page after definiting all the settings!
        $settings->add($page);
    }
}