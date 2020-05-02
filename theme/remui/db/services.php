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
 * @package theme_remui
 * @author  2018 wisdmlabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$functions = array(
    'theme_remui_set_setting' => array(
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'set_setting',
        'description'   => 'Set config',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'theme_remui_save_user_profile_settings' => array(
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'save_user_profile_settings',
        'description'   => 'Save user profile data from profile page',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'theme_remui_send_message' => array(
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'send_message',
        'description'   => 'Send message to user',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'theme_remui_get_course_stats' => array(
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_course_stats',
        'description'   => 'Get course statistics',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'theme_remui_get_courses' => array(
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_courses',
        'description'   => 'Get courses',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => false
    )
);


