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
use context_system;

trait update_section_instance {
    /**
     * Describes the parameters for update_section_instance
     * @return external_function_parameters
     */
    public static function update_section_instance_parameters() {
        return new external_function_parameters(
            array(
                'instanceid' => new external_value(PARAM_INT, 'Section instance id'),
                'jsonformdata' => new external_value(PARAM_RAW, 'Form data in JSON format')
            )
        );
    }

    /**
     * Update config data of section using instance id and section form data
     * @param  [type] $instanceid   Instance id of section
     * @param  [type] $jsonformdata Json form data
     * @return mixed                Update status and json section data
     */
    public static function update_section_instance($instanceid, $jsonformdata) {
        global $DB, $PAGE, $USER;
        $PAGE->set_context(context_system::instance());

        $userisediting = false;
        if (isloggedin()) {
            $usercontext = context_user::instance($USER->id);
            $userisediting = has_capability('local/remuihomepage:editfrontpage', $usercontext) && $PAGE->user_is_editing();
        }

        $output = array();
        $sm = new section_manager();
        $sm->set_instanceid($instanceid);

        $serialiseddata = json_decode($jsonformdata);
        parse_str($serialiseddata, $formdata);
        unset($formdata['_qf__local_remuihomepage_frontpage_sections_main_form']);
        unset($formdata['mform_isexpanded_id_moodle']);
        $formdata = $sm->convert_to_array($formdata);

        $output['success'] = $sm->update_section_instance($formdata) == true;

        $record = $DB->get_record($sm->get_sectiontable(), array('id' => $instanceid));
        $record = $sm->process_section_customcss($record, $userisediting);
        $configdata = $sm->sectiondata($record, $userisediting);
        if (isset($userisediting)) {
            $configdata['userisediting'] = $userisediting;
        }
        if (get_config('theme_remui', 'frontpageloader')) {
            $configdata['loader'] = toolbox::setting_file_url('frontpageloader', 'frontpageloader');
        }
        $configdata = json_encode($configdata);
        $output['context'] = $configdata;

        return $output;
    }

    /**
     * Describes the update_section_instance return value
     * @return exernal_function_parameter
     */
    public static function update_section_instance_returns() {
        return new external_function_parameters(
            array(
                'success' => new external_value(PARAM_BOOL, 'Updation success - true/false'),
                'context' => new external_value(PARAM_RAW, 'Section context data')
            )
        );
    }
}
