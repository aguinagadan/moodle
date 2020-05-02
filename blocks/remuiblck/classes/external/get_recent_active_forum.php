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

trait get_recent_active_forum {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_recent_active_forum_parameters() {
        // get_recent_active_forum_parameters() always return an external_function_parameters().
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
    public static function get_recent_active_forum() {
        global $PAGE;
        session_write_close();
        $PAGE->set_context(context_system::instance());
        $obj = \block_remuiblck\coursehandler::get_instance();
        // Forum Data
        $data = $obj->get_recent_active_forums();
        $response = [];
        if (!empty($data)) {
            $response = $data;
        }
        return $response;
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_recent_active_forum_returns() {
        $userstructure = new external_single_structure(
            array(
                'id'                => new external_value(PARAM_INT, 'id'),
                'firstnamephonetic' => new external_value(PARAM_TEXT, "User's firstnamephonetic"),
                'lastnamephonetic'  => new external_value(PARAM_TEXT, "User's lastnamephonetic"),
                'middlename'        => new external_value(PARAM_TEXT, "User's middlename"),
                'alternatename'     => new external_value(PARAM_TEXT, "User's alternatename"),
                'firstname'         => new external_value(PARAM_TEXT, "User's firstname"),
                'lastname'          => new external_value(PARAM_TEXT, "User's lastname"),
                'picture'           => new external_value(PARAM_INT, "User's picture"),
                'imagealt'          => new external_value(PARAM_TEXT, "User's imagealt"),
                'email'             => new external_value(PARAM_EMAIL, "User's email"),
                'profilepicture'    => new external_value(PARAM_URL, "User's Profile picture url")
            )
        );
        return new external_single_structure(
            array(
                'hasrecentforums'   => new external_value(PARAM_BOOL, 'Do we have recent active forums'),
                'discussurl'        => new external_value(PARAM_URL, 'Discussion url', VALUE_OPTIONAL, ''),
                'forumurl'          => new external_value(PARAM_URL, 'Forum url', VALUE_OPTIONAL, ''),
                'userurl'           => new external_value(PARAM_URL, 'User profile url', VALUE_OPTIONAL, ''),
                'recentforums'      => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'type' => new external_value(PARAM_TEXT, 'Course module type'),
                            'cmid' => new external_value(PARAM_INT, 'Course module id'),
                            'name' => new external_value(PARAM_TEXT, 'Forum query name'),
                            'courseshortname'   => new external_value(PARAM_TEXT, 'Course short name'),
                            'coursefullname'    => new external_value(PARAM_TEXT, 'Course full name'),
                            'forumname'         => new external_value(PARAM_TEXT, 'Forum name'),
                            'sectionnum'        => new external_value(PARAM_INT, 'Section number'),
                            'timestamp'         => new external_value(PARAM_INT, 'Time stamp'),
                            'content'           => new external_single_structure(
                                array(
                                    'id'            => new external_value(PARAM_INT, 'Id of discussion'),
                                    'discussion'    => new external_value(PARAM_INT, 'Discussion number'),
                                    'subject'       => new external_value(PARAM_TEXT, 'Subject text'),
                                    'parent'        => new external_value(PARAM_INT, 'Parent id'),
                                )
                            ),
                            'user'          => $userstructure,
                            'replies'       => new external_value(PARAM_INT, 'Number of replies'),
                            'recentuser'    => new external_multiple_structure(
                                $userstructure
                            )
                        )
                    )
                )
            ),
            'Data for recent feedbacks',
            VALUE_OPTIONAL,
            array()
        );
    }
}
