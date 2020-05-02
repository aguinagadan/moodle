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

use stdClass;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;
use context_system;
use html_writer;

trait get_course_progress_list {

    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_course_progress_list_parameters() {
        // get_course_progress_list_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
                'search'   => new external_value(PARAM_RAW, 'Search query'),
                'start'    => new external_value(PARAM_INT, 'Start index of record'),
                'length'   => new external_value(PARAM_INT, 'Number of records per page'),
                'order'    => new external_single_structure(
                    array(
                        'column' => new external_value(PARAM_INT, 'index of column'),
                        'dir'    => new external_value(PARAM_ALPHA, 'direction of sorting')
                    ),
                    'sorting order with column number and sorting direction'
                )
            )
        );
    }



    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_course_progress_list($search, $start, $length, $order) {
        global $PAGE;
        $PAGE->set_context(context_system::instance());
        $coursehandler = \block_remuiblck\coursehandler::get_instance();
        list($courses, $count) = $coursehandler->teacher_courses_data($search, $start, $length, $order);
        return array(
            "courses" => empty($courses) ? [] : $courses,
            "recordsTotal" => $count,
            "recordsFiltered" => $count
        );
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_course_progress_list_returns() {
        return new external_single_structure(
            array(
                "courses" => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            "index" => new external_value(PARAM_INT, "Row index"),
                            "id" => new external_value(PARAM_INT, "Course id"),
                            "fullname" => new external_value(PARAM_RAW, "Course fullname"),
                            "shortname" => new external_value(PARAM_RAW, "Course shortname"),
                            "category" => new external_value(PARAM_INT, "Course category"),
                            "startdate" => new external_value(PARAM_TEXT, "Course startdate"),
                            "enddate" => new external_value(PARAM_TEXT, "Course enddate"),
                            "timecreated" => new external_value(PARAM_TEXT, "Course timecreated"),
                            "percentage" => new external_value(PARAM_FLOAT, "Course completion percentage"),
                            "enrolledStudents" => new external_value(PARAM_INT, "Students enrolled in course"),
                            "backColor" => new external_value(PARAM_TEXT, "Background color"),
                        )
                    ),
                    'Courses list',
                    VALUE_DEFAULT,
                    []
                ),
                "recordsTotal" => new external_value(PARAM_INT, "Total records found"),
                "recordsFiltered" => new external_value(PARAM_INT, "Total filtered record")
            )
        );
    }
}
