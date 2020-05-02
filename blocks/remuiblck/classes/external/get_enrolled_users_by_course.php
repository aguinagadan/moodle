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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/enrol/externallib.php');

use external_function_parameters;
use external_single_structure;
use external_value;
use core_enrol_external;

trait get_enrolled_users_by_course {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_enrolled_users_by_course_parameters() {
        return core_enrol_external::get_enrolled_users_parameters();
    }
    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_enrolled_users_by_course($courseid, $options = array()) {
        return core_enrol_external::get_enrolled_users($courseid, $options = array());
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_enrolled_users_by_course_returns() {
        return core_enrol_external::get_enrolled_users_returns();
    }
}
