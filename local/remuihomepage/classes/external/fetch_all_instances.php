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
 * @package local_remuihomepage
 * @author  2019 wisdmlabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_remuihomepage\external;

defined('MOODLE_INTERNAL') || die;

use external_function_parameters;
use external_value;
use external_multiple_structure;
use \local_remuihomepage\frontpage\section_manager as section_manager;
use context_user;
use context_system;
use cache;

trait fetch_all_instances {
    /**
     * Describes the parameters for fetch_all_instances
     * @return external_function_parameters
     */
    public static function fetch_all_instances_parameters() {
        return new external_function_parameters(array());
    }

    /**
     * Fetch all instance of sections in array of configuration format
     * @return array sections list
     */
    public static function fetch_all_instances() {
        global $PAGE, $USER;
        $PAGE->set_context(context_system::instance());
        $sm = new section_manager();
        $userisediting = false;
        if (isloggedin()) {
            $usercontext = context_user::instance($USER->id);
            $userisediting = has_capability('local/remuihomepage:editfrontpage', $usercontext) && $PAGE->user_is_editing();
        }
        if ($userisediting) {
            $records = $sm->get_all_instances();
            $sections = $sm->process_all_sections($records);
        } else {
            $cache = cache::make('local_remuihomepage', 'frontpage');
            $sections = $cache->get('sections');
            $sectionslist = $cache->get('sectionslist');
            if (!$sections || !$sectionslist) {
                $records = $sm->get_all_instances();
                list($sections, $sectionslist) = $sm->process_all_sections($records, true);
                $cache->set('sectionslist', $sectionslist);
                $cache->set('sections', $sections);
            }
            if (in_array('courses', $sectionslist)) {
                foreach ($sectionslist as $key => $section) {
                    if ($section == 'courses') {
                        $data = json_decode($sections[$key], true);
                        $record = $sm->get_config_by_instanceid($data['id']);
                        $record = $sm->process_section_customcss($record);
                        $configdata = $sm->sectiondata($record);
                        if (get_config('theme_remui', 'frontpageloader')) {
                            $configdata['loader'] = \theme_remui\toolbox::setting_file_url('frontpageloader', 'frontpageloader');
                        }
                        $sections[$key] = json_encode($configdata);
                    }
                }
            }
        }
        return [
            'sections' => $sections
        ];
    }

    /**
     * Describes the fetch_all_instances return value
     * @return external
     */
    public static function fetch_all_instances_returns() {
        return new external_function_parameters(
            array(
                'sections' => new external_multiple_structure(
                    new external_value(PARAM_RAW, 'Section configuration json'),
                    'Sections json',
                    VALUE_OPTIONAL,
                    []
                )
            )
        );
    }
}
