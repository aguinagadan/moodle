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

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir . "/externallib.php");
require_once($CFG->dirroot . "/blocks/remuiblck/lib.php");
require_once($CFG->dirroot . "/blocks/remuiblck/classes/coursehandler.php");
require_once($CFG->dirroot . "/calendar/lib.php");

use external_api;

class api extends external_api {
    use get_course_report;
    use get_dropping_off_students;
    use export_dropping_off_students;
    use get_user_tasks;
    use create_new_task;
    use edit_task;
    use complete_task;
    use delete_task;
    use task_notify_users;
    use get_manage_courses_list;
    use get_latest_members_list;
    use get_recent_feedbacks;
    use get_recent_active_forum;
    use get_course_progress_list;
    use get_course_progress;
    use get_course_analytics;
    use get_enrolled_users_by_category;
    use get_quiz_participation;
    use get_enrolled_users_by_course;
    use send_message;
}
