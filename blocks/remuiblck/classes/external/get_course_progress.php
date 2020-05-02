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
use context_course;
use context_system;
use theme_remui\utility as utility;
use block_remuiblck\coursehandler as coursehandler;
use core_completion\progress;

trait get_course_progress {

    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_course_progress_parameters() {
        // get_course_progress_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
                'courseid'   => new external_value(PARAM_INT, 'Course id', VALUE_DEFAULT, 0)
            )
        );
    }



    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_course_progress($courseid) {
        global $PAGE;
        $PAGE->set_context(context_system::instance());
        $data = new stdClass;
        $course = get_course($courseid);
        $data->coursefullname = format_text(trim($course->fullname));
        $data->coursesummary = format_text(trim(strip_tags($course->summary)));
        $data->students = [];

        $coursecontext = context_course::instance($courseid);
        $students      = get_role_users(5, $coursecontext);
        $studentcnt   = 0;
        $coursehandler = coursehandler::get_instance();
        foreach ($students as $studentid => $student) {
            $studentdata = new stdClass;
            $studentdata->index = ++$studentcnt;
            $studentdata->name = fullname($student);
            $studentdata->id = $studentid;
            $studentdata->lastaccess = $coursehandler->get_last_course_access_time($courseid, $studentid)->time;
            $progress = (int)progress::get_course_progress_percentage($course, $student->id);
            if (empty($progress)) {
                $progress = 0;
            }
            $studentdata->progress = $progress;
            $studentdata->progressclass = $progress > 70 ? 'progress-bar-success' : ($progress > 30 ? 'progress-bar-warning' : 'progress-bar-danger');
            $data->students[] = $studentdata;
            unset($students[$studentid]);
        }
        return $data;

    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_course_progress_returns() {
        return new external_single_structure(array(
            'coursefullname' => new external_value(PARAM_RAW, 'Course fullname'),
            'coursesummary'  => new external_value(PARAM_RAW, 'Course summary'),
            'students'       => new external_multiple_structure(
                new external_single_structure(array(
                    'index' => new external_value(PARAM_INT, 'Student index'),
                    'id' => new external_value(PARAM_INT, 'Student id'),
                    'name' => new external_value(PARAM_RAW, 'Student name'),
                    'lastaccess' => new external_value(PARAM_TEXT, 'Course last access'),
                    'progress' => new external_value(PARAM_INT, 'Course progress'),
                    'progressclass' => new external_value(PARAM_TEXT, 'Course progress class')
                )),
                'Students course progress details',
                VALUE_DEFAULT,
                []
            )
        ));
    }
}
