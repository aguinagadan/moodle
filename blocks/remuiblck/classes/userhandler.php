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
namespace block_remuiblck;

defined('MOODLE_INTERNAL') || die();
use context_course;
use user_picture;
use moodle_url;

// This class will handle every operations related to users
class userhandler {
    public static $instance = null;

    private function __construct() {
    }

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new userhandler();
        }
        return self::$instance;
    }
    // enrolled_users_state & latest members
    public function enrolled_users_state() {
        global $CFG, $DB;
        $templatecontext = array();
        if (is_siteadmin()) {
            // enrolled_users_state
            if ($CFG->branch >= 36) {
                $categorylist = \core_course_category::make_categories_list();
            } else {
                require_once($CFG->libdir. '/coursecatlib.php');
                $categorylist = \coursecat::make_categories_list();
            }
            $inquery = implode(", ", array_keys($categorylist));
            $sqlq = 'SELECT DISTINCT category from {course} where category IN (' . $inquery . ')';
            $catres = $DB->get_records_sql($sqlq);
            if ($catres) {
                $templatecontext['hascategory'] = true;
                $count = 0;
                foreach ($catres as $key => $value) {
                    $value = $value;
                    $category[$count] = new \stdClass;
                    $category[$count]->key = $key;
                    $category[$count]->categoryname = $categorylist[$key];
                    $count++;
                }
                $templatecontext['category'] = $category;
            }
            // end_enrolled_users_state
        }
        return $templatecontext;
    }

    public function get_quiz_stats() {
        global $DB;
        $templatecontext = array();
        // quiz_stats
        $sqlq = ("SELECT DISTINCT q.course courseid, c.shortname shortname, c.fullname fullname FROM {quiz} q JOIN {course} c ON q.course = c.id");
        $coursesforquiz = $DB->get_records_sql($sqlq);
        foreach ($coursesforquiz as $course) {
            $context = context_course::instance($course->courseid);
            if (!has_capability('mod/quiz:preview', $context)) {
                unset($coursesforquiz[$course->courseid]);
            }
        }
        if ($coursesforquiz) {
            $templatecontext['has_courses_for_quiz'] = true;
            $templatecontext['courses_for_quiz'] = array_values($coursesforquiz);
        }

        return $templatecontext;
        // end_quiz_stats
    }

    public function get_latest_member_data() {
        $templatecontext['latest_members'] = $this->get_recent_user();
        $templatecontext['profile_url'] = new moodle_url('/user/profile.php?id');
        $templatecontext['user_profiles'] = new moodle_url('/admin/user.php');
        return $templatecontext;
    }

    // Get the recently added users
    public function get_recent_user() {
        global  $DB;
        $userdata = array();
        $limitfrom = 0;
        $limitto = 8;
        $users = $DB->get_records_sql('SELECT u.* FROM {user} u  WHERE u.deleted = 0 AND id != 1 ORDER BY timecreated desc', array(1), $limitfrom, $limitto);
        $count = 0;
        foreach ($users as $value) {
            $date = date('d/m/Y', $value->timecreated);
            if ($date == date('d/m/Y')) {
                $date = get_string('today', 'core_calendar');
            } else if ($date == date('d/m/Y', time() - (24 * 60 * 60))) {
                $date = get_string('yesterday', 'core_calendar');
            } else {
                $date = date('jS F Y', $value->timecreated);
            }
            $userdata[$count]['img'] = $this->get_user_image_link($value->id, 100)->out();
            $userdata[$count]['name'] = $value->firstname .' '.$value->lastname;
            $userdata[$count]['register_date'] = $date;
            $userdata[$count]['id'] = $value->id;
            $count++;
        }
        return $userdata;
    }

    // get user profile pic link
    public function get_user_image_link($userid, $imgsize) {
        global $USER;
        if (!$userid) {
            $userid = $USER->id;
        }
        global $DB, $PAGE;
        $user = $DB->get_record('user', array('id' => $userid));
        $userimg = new user_picture($user);
        $userimg->size = $imgsize;
        return  $userimg->get_url($PAGE);
    }

    /**
     * Check whether user is teacher in any enrolled courses
     * @param  int  $userid User id
     * @return boolean         true if is teacher
     */
    public function is_teacher_in_any_course($userid = null) {
        global $USER;
        if ($userid == null) {
            $userid = $USER->id;
        }
        $coursehandler = \block_remuiblck\coursehandler::get_instance();
        $mycourses = $coursehandler->get_user_courses($userid);
        if (!empty($coursehandler->user_is_teacher(array_keys($mycourses), $userid))) {
            return true;
        }
        return false;
    }

    /**
     * Get user roles sytem wide
     * @return array roles array
     */
    public function get_user_roles_system_wide() {
        global $USER, $DB;
        if (is_siteadmin()) {
            return array('admin' => true);
        }
        $sql = "SELECT DISTINCT(r.archetype)
                  FROM {role_assignments} ra, {role} r
                 WHERE ra.userid = ?
                   AND ra.roleid = r.id";
        $roles = $DB->get_records_sql($sql, array($USER->id));
        $roles = array_keys($roles);

        if (empty($roles)) {
            return [];
        }
        return $roles;
    }
}
