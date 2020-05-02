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

trait edit_task {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function edit_task_parameters() {
        // edit_task_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
                'id'      => new external_value(PARAM_INT, 'Id of task'),
                'subject' => new external_value(PARAM_RAW, 'Task subject'),
                'summary' => new external_value(PARAM_RAW, 'Task summary', VALUE_DEFAULT, ''),
                'timedue'     => new external_value(PARAM_INT, 'Due date of task'),
                'visible' => new external_value(PARAM_BOOL, 'Does task visible to assignee'),
                'notify' => new external_value(PARAM_BOOL, 'Should users notify when task is completed'),
                'users'   => new external_multiple_structure(
                    new external_value(PARAM_INT, 'User id'),
                    'Users ids',
                    VALUE_DEFAULT,
                    array()
                ),
            )
        );
    }
    /**
     * The function itself
     * @return string welcome message
     */
    public static function edit_task($id, $subject, $summary, $timedue, $visible, $notify, $users = []) {
        $task = new stdClass;
        $task->id = $id;
        $task->subject = $subject;
        $task->summary = $summary;
        $task->timedue = $timedue;
        $task->visible = $visible;
        $task->notify = $notify;
        $task->completed = 0;
        $task->assignedto = json_encode($users);
        $task->timemodified = time();
        $taskhandler = new \block_remuiblck_taskhandler($id);
        return $taskhandler->update($task);
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function edit_task_returns() {
        return new external_value(PARAM_BOOL, 'Status');
    }
}
