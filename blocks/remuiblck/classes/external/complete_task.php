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
 * @package block_remuiblck
 * @author  2019 WisdmLabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_remuiblck\external;

use external_function_parameters;
use external_single_structure;
use external_multiple_structure;
use external_value;
use stdClass;

trait complete_task {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function complete_task_parameters() {
        // complete_task_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
                'id'     => new external_value(PARAM_INT, 'Id of task'),
                'status' => new external_value(PARAM_BOOL, 'True if task is completed or false if task is incomplete')
            )
        );
    }
    /**
     * The function itself
     * @return string welcome message
     */
    public static function complete_task($id, $status) {
        $taskhandler = new \block_remuiblck_taskhandler($id);
        $result = $taskhandler->complete($status);
        if ($taskhandler->get_task()->notify) {
            $taskhandler->notify_users($status == true ? 'complete' : 'incomplete');
        }
        return array(
            'status' => $result,
            'msg'    => $result == true ? '' : (get_string(
                $status == true ? 'failedtomarkcomplete' : 'failedtomarkincomplete',
                'block_remuiblck'
            ))
        );
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function complete_task_returns() {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'Status of completeion or incompletion'),
                'msg'    => new external_value(PARAM_TEXT, 'Error message if any')
            )
        );
    }
}
