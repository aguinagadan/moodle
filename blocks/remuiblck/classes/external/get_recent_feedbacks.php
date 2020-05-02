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

trait get_recent_feedbacks {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_recent_feedbacks_parameters() {
        // get_recent_feedbacks_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
            )
        );
    }
    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_recent_feedbacks() {
        session_write_close();
        $obj = \block_remuiblck\coursehandler::get_instance();
        // Assignment Data
        $data = $obj->get_recent_assignment();
        $response = [];
        if (!empty($data)) {
            $response['recentdata'] = $data;
        }
        return $response;
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_recent_feedbacks_returns() {
        return new external_single_structure(
            array(
                'recentdata' => new external_single_structure(
                    array(
                        'hasrecentassignments' => new external_value(PARAM_BOOL, 'Do we have recent assignments', VALUE_OPTIONAL, 0),
                        'hasrecentfeedback' => new external_value(PARAM_BOOL, 'Do we have recent feedbacks', VALUE_OPTIONAL, 0),
                        'recentassignments' => new external_multiple_structure(
                            new external_single_structure(
                                array(
                                    'cm_url' => new external_value(PARAM_URL, 'Course module url'),
                                    'cm_name' => new external_value(PARAM_TEXT, 'Course module name'),
                                    'course_fullname' => new external_value(PARAM_TEXT, 'Full name of course')
                                )
                            ),
                            'Recent assignments list',
                            VALUE_OPTIONAL,
                            array()
                        ),
                        'recentfeedback' => new external_multiple_structure(
                            new external_single_structure(
                                array(
                                    'courseurl' => new external_value(PARAM_URL, 'Course url'),
                                    'course_shortname' => new external_value(PARAM_TEXT, 'Course short name'),
                                    'assignurl' => new external_value(PARAM_URL, 'Assignment url'),
                                    'grade_itemname' => new external_value(PARAM_TEXT, 'Grade assignment name'),
                                    'grade_rawgrade' => new external_value(PARAM_FLOAT, 'Raw grade'),
                                    'grade_rawgrademax' => new external_value(PARAM_FLOAT, 'Maximum raw grade'),
                                    'timemodified' => new external_value(PARAM_INT, 'Date of last modification')
                                )
                            ),
                            'Recent feedbacks list',
                            VALUE_OPTIONAL,
                            array()
                        )
                    ),
                    VALUE_OPTIONAL,
                    array()
                )
            ),
            'Data for recent feedbacks',
            VALUE_OPTIONAL,
            array()
        );
    }
}
