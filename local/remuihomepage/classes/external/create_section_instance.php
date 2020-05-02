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
use \local_remuihomepage\frontpage\section_manager as section_manager;
use \theme_remui\toolbox as toolbox;
use context_user;

trait create_section_instance {

    /**
     * Describes the parameters for create_section_instance
     * @return external_function_parameters
     */
    public static function create_section_instance_parameters() {
        return new external_function_parameters(
            array(
                'sectionname' => new external_value(PARAM_RAW, 'JSON data')
            )
        );
    }

    /**
     * Create new section instance and return it json data
     * @param  string $sectionname Name of section
     * @return string              Section json data
     */
    public static function create_section_instance($sectionname) {
        global $PAGE, $USER;
        $sm = new section_manager();
        $sm->set_name($sectionname);
        $data = $sm->create_instance();

        $usercontext = context_user::instance($USER->id);
        $data['userisediting'] = has_capability('local/remuihomepage:editfrontpage', $usercontext) && $PAGE->user_is_editing();
        if (get_config('theme_remui', 'frontpageloader')) {
            $data['loader'] = toolbox::setting_file_url('frontpageloader', 'frontpageloader');
        }
        return json_encode($data);
    }

    /**
     * Describes the create_section_instance return value
     * @return external_value
     */
    public static function create_section_instance_returns() {
        return new external_value(PARAM_RAW, 'JSON data');
    }
}
