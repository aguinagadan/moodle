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
defined('MOODLE_INTERNAL') || die();

$functions = array(
    'block_remuiblck_get_course_report' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_course_report',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get manage courses modal to see user statistic',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_dropping_off_students' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_dropping_off_students',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get dropping off students list ',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_export_dropping_off_students' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'export_dropping_off_students',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Export dropping off students list ',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_user_tasks' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_user_tasks',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get user events data',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_create_new_task' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'create_new_task',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Create new task',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'block_remuiblck_edit_task' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'edit_task',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Edit exsting task',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'block_remuiblck_complete_task' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'complete_task',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Mark task as completed or incomplete',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'block_remuiblck_delete_task' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'delete_task',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Delete exsting task',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'block_remuiblck_task_notify_users' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'task_notify_users',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Notify users using moodle message api',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'block_remuiblck_get_manage_courses_list' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_manage_courses_list',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get manage courses list of teacher',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_latest_members_list' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_latest_members_list',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Fetch the list of members',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_recent_feedbacks' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_recent_feedbacks',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Fetch latest feedbacks for user/teacher',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_recent_active_forum' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_recent_active_forum',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Fetch latest forum',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_course_progress_list' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_course_progress_list',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get course list for course progress block datatable',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_course_progress' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_course_progress',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get course progress',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_course_analytics' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_course_analytics',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get course analytics',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_enrolled_users_by_category' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_enrolled_users_by_category',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get enrolled users count and course details',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_quiz_participation' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_quiz_participation',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get user participation in quiz',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_get_enrolled_users_by_course' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'get_enrolled_users_by_course',
        'classpath'     => 'blocks/remuiblck/externallib.php',
        'description'   => 'Get user enrolled in course',
        'type'          => 'read',
        'ajax'          => true,
    ),
    'block_remuiblck_send_message' => array(
        'classname'     => 'block_remuiblck\external\api',
        'methodname'    => 'send_message',
        'description'   => 'Send message to user',
        'type'          => 'write',
        'ajax'          => true,
    ),
);
