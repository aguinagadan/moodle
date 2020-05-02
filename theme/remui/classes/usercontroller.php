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
 * Edwiser RemUI
 * @package    theme_remui
 * @copyright  (c) 2019 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui;
defined('MOODLE_INTERNAL') || die();

use blog_listing;
use moodle_url;
use core_completion\progress;

require_once($CFG->dirroot.'/mod/forum/lib.php');

class usercontroller {
    /**
     * Get post count of forums in which user is made post or courses in which user is enrolled
     * @param  Object $userobject User object
     * @return Integer            Forum post counts
     */
    public static function get_user_forum_post_count($userobject = null) {
        global $USER;
        if (!$userobject) {
            $userobject = $USER;
        }

        $courses = forum_get_courses_user_posted_in($userobject);
        $userpostcount = forum_get_posts_by_user($userobject, $courses)->totalcount;
        $userpostlink = new moodle_url('/mod/forum/user.php?id=' . $userobject->id);

        return $userpostcount;
    }

    /**
     * Get post count of posts in which user is made post or courses in which user is enrolled
     * @param  Object $userobject User object
     * @return Integer            Posts count
     */
    public static function get_user_blog_post_count($userobject = null) {
        global $USER, $DB, $CFG;
        if (!$userobject) {
            $userobject = $USER;
        }

        if (!empty($CFG->enableblogs)) {
            include_once($CFG->dirroot .'/blog/locallib.php');
        } else {
            return;
        }

        $blogobj = new blog_listing();
        if ($sqlarray = $blogobj->get_entry_fetch_sql(false, 'created DESC')) {
            $sqlarray['sql'] = "SELECT p.*, u.firstnamephonetic, u.lastnamephonetic, u.middlename,
                                       u.alternatename, u.firstname, u.lastname, u.email
                                 FROM {post} p, {user} u
                                WHERE u.deleted = 0
                                  AND p.userid = u.id
                                  AND  (p.module = 'blog' OR p.module = 'blog_external')
                                  AND (p.userid = ?  OR p.publishstate = 'site')
                                  AND u.id = ?
                                ORDER BY created DESC";
            $sqlarray['params'] = array($USER->id, $userobject->id);
            $blogobj->entries = $DB->get_records_sql($sqlarray['sql'], $sqlarray['params']);
            $userblogcount = count($blogobj->entries);
        }

        return $userblogcount;
    }

    /**
     * Count the number of contacts of user
     * @param  Object $userobject User object
     * @return Integer            Users count
     */
    public static function get_user_contacts_count($userobject = null) {
        global $USER, $DB, $CFG;
        if (!$userobject) {
            $userobject = $USER;
        }

        $userblogcount = count($DB->get_records('message_contacts', array('userid' => $userobject->id)));

        return $userblogcount;
    }

    /**
     * Get course progress of user in which user is enrolled
     * @param  Object $userobject User object
     * @return Array              Courses array
     */
    public static function get_users_courses_with_progress($userobject) {
        global $USER, $OUTPUT, $CFG;

        if (!$userobject) {
            $userobject = $USER;
        }

        require_once($CFG->dirroot.'/course/renderer.php');
        $chelper = new \coursecat_helper();

        $courses = enrol_get_users_courses($userobject->id, true, '*', 'visible DESC, fullname ASC, sortorder ASC');
        foreach ($courses as $course) {
            if (is_array($course)) {
                $course = (object)$course;
            }
            $course->fullname = strip_tags($chelper->get_course_formatted_name($course));
            // Get course list instance.
            $courseobj = new \core_course_list_element($course);

            $completion = new \completion_info($course);

            // First, let's make sure completion is enabled.
            if ($completion->is_enabled()) {
                $percentage = progress::get_course_progress_percentage($course, $userobject->id);

                if (!is_null($percentage)) {
                    $percentage = floor($percentage);
                }

                // Add completion data in course object.
                $course->completed = $completion->is_course_complete($userobject->id);
                $course->progress  = $percentage;
            }

            $course->link = $CFG->wwwroot."/course/view.php?id=".$course->id;

            $course->summary = strip_tags($chelper->get_course_formatted_summary(
                $courseobj,
                array('overflowdiv' => false, 'noclean' => false, 'para' => false)
            ));

            // Update course image in object.
            foreach ($courseobj->get_course_overviewfiles() as $file) {
                $isimage = $file->is_valid_image();
                $courseimage = file_encode_url(
                    "$CFG->wwwroot/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(),
                    !$isimage
                );
                if ($isimage) {
                    break;
                }
            }
            if (empty($courseimage)) {
                $courseimage = $OUTPUT->image_url('placeholder', 'theme');
            }

            $course->courseimage = $courseimage;
            $courseimage = '';
        }
        return $courses;
    }

    /**
     * Update the User Profile details using ajax call.
     *
     * @param $fname, $lname, $emailid, $description, $city, $country
     *
     * @return boolean, weather result are updated or not.
     */
    public static function save_user_profile_info($fname, $lname, $description, $city, $country) {
        global $USER, $DB;

        $user = $DB->get_record('user', array('id' => $USER->id));
        $user->firstname = $fname;
        $user->lastname = $lname;
        $user->description = $description;
        $user->city = $city;
        $user->country = $country;
        $result = $DB->update_record('user', $user);

        // Update Global Variable
        $USER->firstname = $fname;
        $USER->lastname = $lname;
        $USER->city = $city;
        $USER->country = $country;

        return $result;
    }
}
