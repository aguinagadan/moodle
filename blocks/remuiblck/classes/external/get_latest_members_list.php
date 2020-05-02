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

trait get_latest_members_list {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_latest_members_list_parameters() {
        // get_latest_members_list_parameters() always return an external_function_parameters().
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
    public static function get_latest_members_list() {
        global $PAGE;
        $PAGE->set_context(context_system::instance());
        $obj = \block_remuiblck\userhandler::get_instance();
        $data = $obj->get_latest_member_data();
        $context                 = new stdClass;
        $context->latest_members = $data['latest_members'];
        $context->profile_url    = $data['profile_url']->out();
        $context->user_profiles  = $data['user_profiles']->out();
        return $context;
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_latest_members_list_returns() {
        return new external_single_structure(
            array(
                "latest_members" => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id'            => new external_value(PARAM_INT, 'Id of task'),
                            'name'          => new external_value(PARAM_TEXT, 'Name of user'),
                            'img'           => new external_value(PARAM_URL, 'User image'),
                            'register_date' => new external_value(PARAM_TEXT, 'Date when account is registered')
                        )
                    ),
                    'Members list',
                    VALUE_OPTIONAL,
                    array()
                ),
                "profile_url"   => new external_value(PARAM_URL, 'Profile base url'),
                "user_profiles" => new external_value(PARAM_URL, 'User list page url')
            )
        );
    }
}
