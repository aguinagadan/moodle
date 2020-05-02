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
use external_value;
use external_single_structure;
use context_system;

trait export_dropping_off_students {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function export_dropping_off_students_parameters() {
        // export_dropping_off_students_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
                'courseid' => new external_value(PARAM_INT, 'Course id'),
                'search'   => new external_value(PARAM_RAW, 'Search query')
            )
        );
    }



    /**
     * The function itself
     * @return string welcome message
     */
    public static function export_dropping_off_students($courseid, $search) {
        global $PAGE, $DB;
        $PAGE->set_context(context_system::instance());
        $coursehandler = \block_remuiblck\coursehandler::get_instance();
        $users = $coursehandler->get_filtered_dropping_user_stats($courseid, $search, false, false, false, false);
        $json = array();
        $yettostart = get_string('yettostart', 'block_remuiblck');
        $currenttime = time();
        $json[] = implode(',', array(
            "name"           => get_string('name'),
            "email"          => get_string('email'),
            "enroltimestart" => get_string('enrolmentdate', 'block_remuiblck'),
            "lastaccess"     => get_string('lastaccess')
        ));
        foreach ($users as $user) {
            $json[] = implode(',', array(
                "name"           => $user->name,
                "email"          => $user->email,
                "enroltimestart" => date("h:i A d F Y", $user->timestart),
                "lastaccess"     => self::get_user_last_access($user->timecreated, $currenttime, $yettostart, false)
            ));
        }
        $coursename = $DB->get_field('course', 'shortname', array('id' => $courseid));
        return array(
            "filename" => $coursename . date("h:i-A,d-F-Y", time()) . '.csv',
            "filedata" => implode("\n", $json)
        );
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function export_dropping_off_students_returns() {
        return new external_single_structure(
            array(
                "filename" => new external_value(PARAM_TEXT, "Name of the file"),
                "filedata" => new external_value(PARAM_RAW, "File data"),
            )
        );
    }
}
