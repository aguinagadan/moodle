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
 * Class User Handler.
 *
 * @package block_remuiblck
 * @author  2019 WisdmLabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


// This class will handle every operations related to users
class block_remuiblck_taskhandler {

    private $taskid;

    private $task = null;

    private $messagetypes = ['create', 'complete', 'incomplete', 'edit', 'added', 'removed'];

    /**
     * Constructor for taskhandler
     *
     * @param int      $taskid id of task. -1 if new task
     * @param stdClass $task   task object. null if new task
     */
    public function __construct($taskid = -1, $task = null) {
        global $DB;
        $this->taskid = $taskid;
        if ($taskid != -1 && $task == null) {
            $this->task = $DB->get_record('block_remuiblck_taskslist', array('id' => $taskid));
        } else if ($task != null) {
            $this->task = $task;
        }
    }

    /**
     * Get task object
     *
     * @return stdClass task object
     */
    public function get_task() {
        return $this->task;
    }

    /**
     * Generate user list for user selection dropdown in task form
     *
     * @param  array $users users array
     *
     * @return array        associative array with user full name at user id index
     */
    public function generate_user_list($users) {
        foreach ($users as $id => $user) {
            $users[$id] = fullname($user) . " (" . $user->email . ")";;
        }
        return $users;
    }

    /**
     * Check whether user is manager or teacher or non-editing teacher in course in any course.
     * m for manager
     * t for teacher
     * n for non-editingteacher
     *
     * @param int $userid User id
     *
     * @return array If is mtnt in any course then returns course ids array or empty array
     */
    private function is_mtn_in_any_course($userid = 0) {
        global $DB, $USER;
        if ($userid == 0) {
            $userid = $USER->id;
        }
        list($insql, $inparams) = $DB->get_in_or_equal(["manager", "editingteacher", "teacher"], SQL_PARAMS_NAMED);
        $sql = "SELECT DISTINCT(c.id)
                  FROM {course} c
                  JOIN {context} ctx ON c.id = ctx.instanceid AND ctx.contextlevel = :contextlevel
                  JOIN {enrol} e ON c.id = e.courseid
                  JOIN {user_enrolments} ue ON e.id = ue.enrolid
                  JOIN {user} u ON ue.userid = u.id
                  JOIN {role_assignments} ra ON ctx.id = ra.contextid AND u.id = ra.userid
                  JOIN {role} r ON ra.roleid = r.id
                 WHERE u.id = :userid
                   AND c.visible = 1
                   AND r.archetype $insql";
        return $DB->get_records_sql($sql, array_merge(array('contextlevel' => CONTEXT_COURSE, 'userid' => $userid), $inparams));
    }

    /**
     * Returns all users enrolled in courses
     *
     * @param array $courses course ids array
     *
     * @return array user id and name
     */
    private function get_users_from_courses($courses) {
        global $DB;
        $namefields = get_all_user_name_fields(true, 'u');
        list($insql, $inparams) = $DB->get_in_or_equal($courses, SQL_PARAMS_NAMED);
        $sql = "SELECT DISTINCT(u.id), $namefields, u.email
                  FROM {course} c
                  JOIN {context} ctx ON c.id = ctx.instanceid AND ctx.contextlevel = :contextlevel
                  JOIN {enrol} e ON c.id = e.courseid
                  JOIN {user_enrolments} ue ON e.id = ue.enrolid
                  JOIN {user} u ON ue.userid = u.id
                 WHERE c.id $insql
                   AND c.visible = 1
                   AND u.confirmed = 1
                   AND u.deleted = 0
                   AND u.suspended = 0";
        return $DB->get_records_sql($sql, array_merge(array('contextlevel' => CONTEXT_COURSE), $inparams));
    }

    /**
     * Get users for task form
     *
     * @return array associative array with user full name at user id index
     */
    public function get_users() {
        global $USER, $DB;

        // User list for admin, site manager , site coursecreator
        if (is_siteadmin($USER->id) || has_capability('moodle/site:configview', context_system::instance(), $USER->id)) {
            $namefields = get_all_user_name_fields(true);
            $users = $DB->get_records_sql(
                'SELECT id, ' . $namefields . ', email
                   FROM {user}
                  WHERE id <> 1 AND deleted <> 1 AND suspended <> 1'
            );
            return $this->generate_user_list($users);
        }
        $courses = $this->is_mtn_in_any_course($USER->id);
        if (!empty($courses)) {
            $users = $this->get_users_from_courses(array_keys($courses));
            return $this->generate_user_list($users);
        }
        return array(
            $USER->id => fullname($USER) . " (" . $USER->email . ")"
        );
    }

    /**
     * Slice user array if users are more than 3. Return array with sliced user count
     * @param  Arry  $users User list
     * @return Array        [$slices, remaining count]
     */
    public function get_sliced_users($users) {
        global $USER;
        $sliced = array_slice($users, 0 , 3, true);
        if (in_array($USER->id, $users) & !in_array($USER->id, $sliced)) {
            $sliced[0] = $USER->id;
        }
        return array($sliced, count($users) - 3);
    }

    /**
     * Fetch users details for task using userids
     *
     * @param  array $userids Ids of user
     *
     * @return array          users detail
     */
    public function get_task_users_details() {
        global $DB;
        $userids = json_decode($this->task->assignedto, true);
        if (count($userids) > 4) {
            list($userids, $more) = $this->get_sliced_users($userids);
        }
        $users = $DB->get_records_list('user', 'id', $userids);
        foreach ($userids as $key => $id) {
            $user = new stdClass;
            $user->name = fullname($users[$id]);
            $user->profile = \theme_remui\utility::get_user_picture($users[$id], 40)->__toString();
            $userids[$key] = $user;
        }
        if (isset($more)) {
            $user = new stdClass;
            $user->name = '';
            $user->profile = '';
            $user->count = $more;
            $userids[] = $user;
        }
        return $userids;
    }

    /**
     * Returns users names in comma separated format
     *
     * @return string user's name
     */
    public function get_users_name_exploded_list() {
        global $DB;
        $userids = json_decode($this->task->assignedto, true);
        $users = $DB->get_records_list('user', 'id', $userids);
        $userfullnames = [];
        foreach ($users as $user) {
            $userfullnames[] = fullname($user);
        }
        return $userfullnames;
    }

    /**
     * Get message to notify user
     *
     * @param string $type type of notification
     *
     * @return string message
     */
    private function get_message($type) {
        global $USER, $CFG;
        if (!in_array($type, $this->messagetypes)) {
            return false;
        }
        // Parameters for subject for get_string
        $subjectparam = array(
            'user'      => fullname($USER),
            'createdby' => fullname($USER),
            'subject'   => $this->task->subject
        );

        // Parameters for message for get_string
        $bodyparam = array(
            'subject'     => $this->task->subject,
            'summary'     => $this->task->summary,
            'user'        => fullname($USER),
            'timedue'     => date('D, M d, Y', $this->task->timedue),
            'completedon' => date('D, M d, Y H:i:s', time())
        );

        // Add assignedto parameter if task is create or incomplete
        if (in_array($type, array('create', 'incomplete', 'edit', 'added', 'removed'))) {
            $userfullnames           = $this->get_users_name_exploded_list();
            $bodyparam['assignedto'] = implode(', ', $userfullnames);
        }

        $subject = get_string($type . 'subject', 'block_remuiblck', $subjectparam);
        $body    = get_string($type . 'message', 'block_remuiblck', $bodyparam);

        $message = new stdClass;
        $message->component         = 'moodle';
        $message->eventtype         = 'instantmessage';
        $message->useridfrom        = $USER->id;
        $message->subject           = $subject;
        $message->fullmessage       = $body;
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->fullmessagehtml   = $body;
        $message->smallmessage      = $body;
        $message->contexturl        = $CFG->wwwroot . '/my/';
        $message->contexturlname    = get_string('mydashboard', 'core_admin');
        $message->timecreated       = time();
        return $message;
    }

    /**
     * Send message by inserting message into database
     * @param  Object $message Message obejct
     */
    private function send_message($message) {
        global $DB;
        $id = $DB->insert_record('notifications', $message);
        $DB->insert_record('message_popup_notifications', array(
            'notificationid' => $id
        ));
    }

    /**
     * Notify course creator and assignee about task
     *
     * @param string $type type of notification
     * @param array $users users array to whom notification should be sent
     */
    public function notify_users($type, $users = array()) {
        global $USER, $PAGE;
        $PAGE->set_context(context_system::instance());
        $message = $this->get_message($type);
        if ($message == false) {
            return false;
        }
        if ($this->task->createdby != $USER->id) {
            $message->useridto = $this->task->createdby;
            $this->send_message($message);
        }
        if (empty($users)) {
            $users = json_decode($this->task->assignedto, true);
        }
        foreach (array_diff($users, [$USER->id, $this->task->createdby]) as $userid) {
            $message->useridto = $userid;
            $this->send_message($message);
        }
        return true;
    }

    /**
     * Mark task as completed or incomplete
     *
     * @param  bool $status True if task is completed or false if task is incomplete
     *
     * @return bool         completion operation status
     */
    public function complete($status) {
        global $USER, $DB;
        if ($this->taskid == -1) {
            return false;
        }
        if ($this->task == null || $this->task == false) {
            return false;
        }
        $task = new stdClass;
        $task->id = $this->taskid;
        $task->completed = $status == true ? $USER->id : 0;
        $task->timemodified = time();
        $result = $DB->update_record('block_remuiblck_taskslist', $task);
        return $result;
    }

    /**
     * Mark task as deleted
     *
     * @return bool         deletion operation status
     */
    public function delete() {
        global $DB;
        if ($this->taskid == -1) {
            return false;
        }
        if ($this->task == null || $this->task == false) {
            return false;
        }
        $task = new stdClass;
        $task->id = $this->taskid;
        $task->deleted = true;
        $task->timemodified = time();
        return $DB->update_record('block_remuiblck_taskslist', $task);
    }

    /**
     * Return newly added, removed and no change assignee list
     * @param  Object $task Task object
     * @return Array        [$added, $removed, $nochange]
     */
    private function assignee_changed($task) {
        $current = json_decode($task->assignedto, true);
        $old = json_decode($this->task->assignedto, true);
        $added = array_diff($current, $old);
        $removed = array_diff($old, $current);
        $nochange = array_diff($current, $added, $removed);
        return array($added, $removed, $nochange);
    }

    /**
     * Update task using task object
     * @param  Object  $task Task object
     * @return Boolean       Updation status
     */
    public function update($task) {
        global $DB;
        $status = $DB->update_record('block_remuiblck_taskslist', $task);
        if ($task->notify) {
            list($added, $removed, $nochange) = $this->assignee_changed($task);
            $this->task->assignedto = $task->assignedto;
            $this->notify_users('edit', $nochange);
            $this->notify_users('added', $added);
            $this->notify_users('removed', $removed);
        }
        return $status;
    }

    /**
     * Check if current user is creator or assignee in the task
     * @return boolean True if current user is creator or assignee of tasl
     */
    public function is_my_task() {
        global $USER;
        if ($this->task == null) {
            return false;
        }
        if ($this->task->createdby == $USER->id) {
            return true;
        }
        $userids = json_decode($this->task->assignedto, true);
        return !empty($userids) && in_array($USER->id, $userids);

    }

    /**
     * Get sql query to fetch task from database
     * @return string sql query
     */
    public static function get_task_sql() {
        global $CFG;
        $sql = 'SELECT id, subject, summary, createdby, completed, visible, timedue, assignedto
                  FROM {block_remuiblck_taskslist}
                 WHERE deleted <> 1
                   AND (createdby = ? OR (assignedto LIKE ? AND visible = 1))';
        return $sql;
    }
}
