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

trait get_dropping_off_students {
    /**
     * Get class for user last access
     *
     * @param string $type type of parameter
     * @param int $value date value
     *
     * @return string classname string
     */
    public static function get_user_last_access_class($type, $value) {
        switch ($type) {
            case 'y':
            case 'm':
                return 'red-600 bg-red-100 p-1';
            case 'd':
                if ($value > 6) {
                    return 'red-600 bg-red-100 p-1';
                }
                if ($value > 3) {
                    return 'orange-600 bg-orange-100 p-1';
                }
        }
        return 'green-600 bg-green-100 p-1';
    }

    /**
     * Get user last access column value
     *
     * @param int $timecreated time when user is enrolled
     * @param int $currenttime
     * @param string $yettostart yet to start label
     * @param bool $table does this call is from table or export button
     *
     * @return string last access column html value
     */
    public static function get_user_last_access($timecreated, $currenttime, $yettostart, $table) {
        $yettostartwrapped = html_writer::tag(
            'label',
            $yettostart,
            array(
                'class' => 'grey-600 bg-grey-200 p-1 w-p100 text-center mb-0'
            )
        );
        if (is_null($timecreated) && $timecreated == 0) {
            return $table ? $yettostartwrapped : $yettostart;
        }
        $difference = get_date_difference($timecreated, $currenttime);
        $accessed = "";
        $values = array(
            "y" => "numyear",
            "m" => "nummonth",
            "d" => "numday",
            "h" => "numhour",
            "i" => "numminute",
            "s" => "numsecond"
        );
        foreach ($values as $key => $id) {
            if ($difference->$key == 0) {
                continue;
            }
            $accessed = get_string($id, 'block_remuiblck', $difference->$key);
            $accessed = get_string('ago', 'core_message', $accessed);
            if (!$table) {
                return $accessed;
            }
            $clockicon = html_writer::tag(
                'i',
                '',
                array(
                    'class' => 'fa fa-clock-o mr-1',
                    'aria-hidden' => 'true'
                )
            ) . $accessed;
            $customclass = self::get_user_last_access_class($key, $difference->$key);
            return html_writer::tag(
                'label',
                $clockicon,
                array(
                    'class' => $customclass . ' w-p100 text-center mb-0'
                )
            );
        }
        return $yettostartwrapped;
    }

    /**
     * Get message icon
     *
     * @param int $userid id of user
     * @param string $content content to attach with message icon
     *
     * @return string message icon html
     */
    public static function get_user_message_icon($userid, $content = "") {
        return html_writer::tag(
            'i',
            '',
            array(
                'class' => 'fa fa-envelope float-right text-success p-1 dropping-student-message',
                'aria-hidden' => 'true',
                'data-student-id' => $userid
            )
        ) . $content;
    }

    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_dropping_off_students_parameters() {
        // get_dropping_off_students_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
                'courseid' => new external_value(PARAM_INT, 'Course id'),
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
    public static function get_dropping_off_students($courseid, $search, $start, $length, $order) {
        global $PAGE;
        $PAGE->set_context(context_system::instance());
        $coursehandler = \block_remuiblck\coursehandler::get_instance();
        $users = $coursehandler->get_filtered_dropping_user_stats($courseid, $search, true, $start, $length, $order);
        $count = $coursehandler->get_filtered_dropping_user_stats_count($courseid, $search);
        $json = array();
        $yettostart = get_string('yettostart', 'block_remuiblck');
        $currenttime = time();
        foreach ($users as $user) {
            $json[] = array(
                "name"           => $coursehandler->get_user_image_and_link($user, $courseid),
                "email"          => $user->email,
                "enroltimestart" => self::get_user_message_icon($user->id, date("h:i A, d F Y", $user->timestart)),
                "lastaccess"     => self::get_user_last_access(
                    $user->timecreated,
                    $currenttime,
                    $yettostart,
                    true
                )
            );
        }
        return array(
            "data" => $json,
            "recordsTotal" => $count,
            "recordsFiltered" => $count
        );
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_dropping_off_students_returns() {
        return new external_single_structure(
            array(
                "data" => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            "name"           => new external_value(PARAM_RAW, "Name of user"),
                            "email"          => new external_value(PARAM_TEXT, "Email of user"),
                            "enroltimestart" => new external_value(PARAM_RAW, "Enrol time start"),
                            "lastaccess"     => new external_value(PARAM_RAW, "Last access time")
                        )
                    )
                ),
                "recordsTotal" => new external_value(PARAM_INT, "Total records found"),
                "recordsFiltered" => new external_value(PARAM_INT, "Total filtered record")
            )
        );
    }
}
