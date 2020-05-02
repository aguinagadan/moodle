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

trait delete_section_instance {

    /**
     * Describes the parameters for delete_section_instance
     * @return external_function_parameters
     */
    public static function delete_section_instance_parameters() {
        return new external_function_parameters(
            array(
                'instanceid' => new external_value(PARAM_INT, 'JSON data'),
                'action' => new external_value(PARAM_BOOL, 'Cancel deletion or delete', VALUE_DEFAULT, true)
            )
        );
    }

    /**
     * Delete section instance using the instance id
     * @param  [type]  $instanceid Instance id of section
     * @param  boolean $action     true to delete and false to cancel deletion
     * @return mixed               Delete/Un delete status and configdata
     */
    public static function delete_section_instance($instanceid, $action = true) {
        global $CFG, $DB, $PAGE, $USER;

        $PAGE->set_context(context_system::instance());

        // Check whether user has capability to edit frontpage.
        $usercontext = context_user::instance($USER->id);
        $userisediting = has_capability('local/remuihomepage:editfrontpage', $usercontext) && $PAGE->user_is_editing();

        $output = array();
        $sm = new section_manager();
        $sm->set_instanceid($instanceid);

        $output["success"] = $status = $sm->mark_instance_deleted($action) != 0;

        $record = $DB->get_record($sm->get_sectiontable(), array('id' => $instanceid));
        $record = $sm->process_section_customcss($record, $userisediting);
        $configdata = $sm->sectiondata($record, $userisediting);
        if (isset($userisediting)) {
            $configdata['userisediting'] = $userisediting;
            $configdata['deleted'] = $record->deleted == true;
        }
        if (get_config('theme_remui', 'frontpageloader')) {
            $configdata['loader'] = toolbox::setting_file_url('frontpageloader', 'frontpageloader');
        }
        $configdata = json_encode($configdata);
        $output['context'] = $configdata;
        return $output;
    }

    /**
     * Describes the delete_section_instance return value
     * @return external_function_parameter
     */
    public static function delete_section_instance_returns() {
        return new external_function_parameters(
            array(
                'success' => new external_value(PARAM_BOOL, 'Deletion success - true/false'),
                'context' => new external_value(PARAM_RAW, 'Section context data')
            )
        );
    }
}
