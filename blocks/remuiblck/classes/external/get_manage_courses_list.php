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

trait get_manage_courses_list {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_manage_courses_list_parameters() {
        // get_manage_courses_list_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
                array(
                    'type' => new external_value(PARAM_TEXT, 'Type of list', VALUE_DEFAULT, 'card'),
                    'perpage' => new external_value(PARAM_INT, 'Number of courses per page', VALUE_DEFAULT, 5),
                    'currentpage' => new external_value(PARAM_INT, 'Currently selected page', VALUE_DEFAULT, 1)
                )
        );
    }

    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_manage_courses_list($type, $perpage, $currentpage) {
        global $PAGE;
        $PAGE->set_context(context_system::instance());

        $obj = \block_remuiblck\coursehandler::get_instance();
        // Courses Data
        $summary = $type == 'summary';
        list($courses, $totalcourses, $start, $end, $totalpages) = $obj->teacher_manage_courses_data($perpage, $currentpage, $summary);
        return array(
            'courses' => $courses,
            'totalcourses' => $totalcourses,
            'start' => $start,
            'end' => $end,
            'totalpages' => $totalpages
        );
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_manage_courses_list_returns() {
        return new external_single_structure(
            array(
                "courses" => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Course id'),
                            'fullname' => new external_value(PARAM_TEXT, 'Course fullname'),
                            'summary' => new external_value(PARAM_RAW, 'Course summary', VALUE_OPTIONAL, get_string('nosummary', 'block_remuiblck')),
                            'startdate' => new external_value(PARAM_INT, 'Course startdate'),
                            'courseimage' => new external_value(PARAM_RAW, 'Course courseimage')
                        )
                    ),
                    'Task list',
                    VALUE_OPTIONAL,
                    array()
                ),
                "totalcourses" => new external_value(PARAM_INT, 'Total number of courses'),
                "start" => new external_value(PARAM_INT, 'Starting of courses'),
                "end" => new external_value(PARAM_INT, 'Ending of courses'),
                "totalpages" => new external_value(PARAM_INT, 'Total number of pages')
            )
        );
    }
}
