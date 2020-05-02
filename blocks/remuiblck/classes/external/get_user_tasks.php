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
use context_system;

trait get_user_tasks {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_user_tasks_parameters() {
        // get_user_tasks_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
                array(
                    'duration' => new external_value(PARAM_TEXT, 'Due duration of tasks'),
                    'status'   => new external_value(PARAM_TEXT, 'Status of task like complete, incomplete, etc.'),
                    'search'   => new external_value(PARAM_RAW, 'Search query for tasks')
                )
        );
    }

    /**
     * Return sql query parameters to filter task based on duration
     *
     * @param string $duration due duration of tasks filter
     *
     * @return array [sql => sql query, param => sql parameter]
     */
    public static function get_task_duration_query_params($duration) {
        $today = strtotime(date('d-m-Y', time()));
        $oneday = 86400; // 24 * 60 * 60
        switch ($duration) {
            case 'today':
                return array(' AND (timedue BETWEEN ?+1 AND ?-1) ', array($today, $today + $oneday));
            case 'next7days':
                return array(' AND (timedue BETWEEN ?+1 AND ?-1) ', array($today, $today + (7 * $oneday)));
            case 'next30days':
                return array(' AND (timedue BETWEEN ?+1 AND ?-1) ', array($today, $today + (30 * $oneday)));
            case 'next3months':
                return array(' AND (timedue BETWEEN ?+1 AND ?-1) ', array($today, $today + (90 * $oneday)));
            case 'next6months':
                return array(' AND (timedue BETWEEN ?+1 AND ?-1) ', array($today, $today + (180 * $oneday)));
            default: // For all
                return array('', array());
        }
    }

    /**
     * Return sql query parameters to filter task based on status
     *
     * @param string $status status of task
     *
     * @return string sql query
     */
    public static function get_task_status_query_params($status) {
        switch ($status) {
            case 'completed':
                return ' AND completed <> 0';
            case 'incomplete':
                return ' AND completed = 0';
            case 'due':
                return ' AND timedue <= ' . time();
            default: // For all
                return '';
        }
    }

    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_user_tasks($duration, $status, $search) {
        global $DB, $USER, $PAGE;
        $response = array();
        $PAGE->set_context(context_system::instance());
        $sql = \block_remuiblck_taskhandler::get_task_sql();
        $params = array($USER->id, "%$USER->id%");

        // Check for duration filter
        list($durationsql, $durationparam) = self::get_task_duration_query_params($duration);
        $sql .= $durationsql;
        $params = array_merge($params, $durationparam);

        // Check for status filter
        $statussql = self::get_task_status_query_params($status);
        $sql .= $statussql;

        if ($search != "") {
            // Search tasks by search query
            $sql .= ' AND (subject LIKE ? OR summary LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
            $response['search'] = $search;
        }

        // Order task according to timedue
        $sql .= ' ORDER BY completed ASC, timedue ASC';
        $result = $DB->get_records_sql($sql, $params);
        $tasks = [];
        $today = time();
        foreach ($result as $id => $task) {
            $taskhandler = new \block_remuiblck_taskhandler($id, $task);
            if (!$taskhandler->is_my_task()) {
                continue;
            }
            if ($USER->id != $task->createdby) {
                $task->createdby = 0;
            }
            $task->summary = $task->summary == '' ? get_string('nosummary', 'block_remuiblck') : $task->summary;
            $task->completed = $task->completed != 0;
            $task->due = ($task->completed == 0 && $task->timedue < $today);
            $task->timedue = date('D, M d, Y', $task->timedue);
            $task->assignedto = $taskhandler->get_task_users_details();
            $tasks[$id] = $task;
        }
        $response['tasks'] = $tasks;
        return $response;
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_user_tasks_returns() {
        return new external_single_structure(
            array(
                "search" => new external_value(PARAM_TEXT, 'Search query', VALUE_OPTIONAL, ''),
                "tasks" => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id'         => new external_value(PARAM_INT, 'Id of task'),
                            'subject'    => new external_value(PARAM_RAW, 'Subject of task'),
                            'summary'    => new external_value(PARAM_RAW, 'Summary of task'),
                            'createdby'  => new external_value(PARAM_INT, 'If of task creator'),
                            'completed'  => new external_value(PARAM_BOOL, 'Does task is complete or incomplete'),
                            'visible'    => new external_value(PARAM_BOOL, 'Is task is visible to assignees'),
                            'timedue'    => new external_value(PARAM_RAW, 'Due date of task'),
                            'due'        => new external_value(PARAM_BOOL, 'Is task is due or has time'),
                            'assignedto' => new external_multiple_structure(
                                new external_single_structure(
                                    array(
                                        'name'    => new external_value(PARAM_TEXT, 'User fullname'),
                                        'profile' => new external_value(PARAM_RAW, 'Profile image link'),
                                        'count'   => new external_value(PARAM_INT, 'Number of users', VALUE_OPTIONAL, 0)
                                    )
                                ),
                                'List of users to whome task is assigned'
                            )
                        )
                    ),
                    'Task list'
                )
            )
        );
    }
}
