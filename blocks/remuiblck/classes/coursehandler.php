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

namespace block_remuiblck;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir. '/completionlib.php'); // required by completion_info

use core_completion\progress;
use user_picture;
use html_writer;
use context_course;
use moodle_url;
use stdClass;
use completion_info;
use context_system;

require_once($CFG->dirroot.'/course/renderer.php');
require_once($CFG->libdir . '/grade/constants.php');
require_once($CFG->dirroot. '/grade/querylib.php'); // required by get_analytics_overview
require_once($CFG->libdir . '/outputrenderers.php');

class coursehandler {

    /**
     * $instance Static property to store singletone object
     * @var coursehandler
     */
    public static $instance = null;

    /**
     * $userscourses User course array
     * @var Array
     */
    public $userscourses = null;

    /**
     * Private custructor of class to prevent direct instance creation
     */
    private function __construct() {
    }

    /**
     * Get singltone instance of coursehandler class
     * @return coursehandler Course handler instance
     */
    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new coursehandler();
        }
        return self::$instance;
    }

    /**
     * Get users all enrolled course and store in userscourses property
     * @return Array Users all enrolled courses
     */
    public function get_user_courses($userid = null) {
        global $USER;
        if ($userid == null) {
            $userid = $USER->id;
        }
        if ($this->userscourses == null) {
            $this->userscourses = enrol_get_users_courses($userid, true, 'enddate, timecreated');
        }
        return $this->userscourses;
    }


    /**
     * Get course overview details
     * @return Array [completionCourses, activityCount,activitiesProgress, courseProgress]
     */
    public function get_course_overview() {
        global $USER;

        $activitycount = 0;
        $courseprogress = 0;
        $activitiesprogress = 0;
        $completioncourses = 0;
        $completionactivities = 0;

        $courses = $this->get_user_courses();

        foreach ($courses as $course) {
            $completion = new \completion_info($course);
            if ($completion->is_enabled()) {
                $percentage = progress::get_course_progress_percentage($course, $USER->id);
                if (!empty($percentage)) {
                    $courseprogress += floor($percentage);
                }
                $completioncourses++;
            }

            // Get all the actvities of the courses
            $allactivities = get_array_of_activities($course->id);
            $activitycount += count($allactivities);

            // Get completed activities percentage
            $modules = $completion->get_activities();
            foreach ($modules as $module) {
                $moduledata = $completion->get_data($module, false, $USER->id);
                $activitiesprogress += $moduledata->completionstate == COMPLETION_INCOMPLETE ? 0 : 1;
                $completionactivities++;
            }
        }
        $data = array();

        $data['completionCourses'] = $completioncourses;
        $data['activityCount'] = $activitycount;
        $data['activitiesProgress'] = $activitiesprogress;
        $data['courseProgress'] = $courseprogress;
        return $data;
    }

    /**
     * Get notes data
     * @return Array courses array
     */
    public function get_notes_data() {
        global $USER;
        $courses = enrol_get_users_courses($USER->id);
        unset($courses[1]);
        return $courses;
    }

    /**
     * Get course progress for students role
     * @param  Object $course       Course object
     * @param  Array  $studentroles Student roles
     * @return Object               Course progress
     */
    public function get_course_progress($course, $studentroles) {
        global $DB;
        $percentage = 0;

        $coursecontext = context_course::instance($course->id);

        $students = [];
        foreach ($studentroles as $studentrole) {
            $result = get_role_users($studentrole->id, $coursecontext);
            $students = array_merge($students, $result);
        }

        foreach ($students as $student) {
            $percentage += progress::get_course_progress_percentage($course, $student->id);
        }

        $courseprogress = new stdClass();
        $courseprogress->id = $course->id;
        $courseprogress->fullname  = format_text($course->fullname);
        $courseprogress->shortname = format_text($course->shortname);
        $courseprogress->category  = $course->category;
        $courseprogress->startdate = date("Y M, d", substr($course->startdate, 0, 10));
        $courseprogress->enddate   = date("Y M, d", substr($course->enddate, 0, 10));
        $courseprogress->timecreated = $course->timecreated;

        $courseprogress->percentage = 0;

        if (0 != count($students)) {
            $courseprogress->percentage = ceil(round($percentage / count($students), 2));
        }

        $courseprogress->enrolledStudents = count($students);

        return  $courseprogress;
    }

    /**
     * Get courses data where user is enrolled as teacher
     * @param  String  $search Search query
     * @param  Integer $start  Start index of course
     * @param  Integer $length End index of course
     * @param  Array   $order  In which order courses should be arranged
     * @return Array         Courses data
     */
    public function teacher_courses_data($search, $start, $length, $order) {
        global $USER, $PAGE, $DB;
        $PAGE->set_context(context_system::instance());
        // Teacher View Dashboard
        $courses = $this->get_user_courses();
        if ($search) {
            $mycourses = [];
            foreach ($courses as $key => $course) {
                if (stripos($course->fullname, $search) !== false) {
                    $mycourses[$key] = $course;
                }
            }
        } else {
            $mycourses = $courses;
        }
        $filtered = $this->user_is_teacher(array_keys($mycourses), $USER->id);
        if (!empty($filtered)) {
            foreach ($filtered as $key => $value) {
                $filtered[$key] = $mycourses[$value];
            }
        }
        unset($courses);
        unset($mycourses);
        $totalcourses = count($filtered);
        $step = 1;
        $coursecount = $start;
        $sort = ['index', 'course', 'startdate'];
        $col = $order['column'];
        $dir = $order['dir'];
        switch ($sort[$col]) {
            case 'index':
                if ($dir == 'asc') {
                    usort($filtered, function($a, $b) {
                        return $a->id > $b->id;
                    });
                } else {
                    usort($filtered, function($a, $b) {
                        return $a->id < $b->id;
                    });
                    $coursecount = $totalcourses - ($start - 1);
                    $step  = -1;
                }
                break;
            case 'course':
                if ($dir == 'asc') {
                    usort($filtered, function($a, $b) {
                        return strcmp($a->fullname, $b->fullname) > 0;
                    });
                } else {
                    usort($filtered, function($a, $b) {
                        return strcmp($a->fullname, $b->fullname) < 0;
                    });
                }
                break;
            case 'startdate':
                if ($dir == 'asc') {
                    usort($filtered, function($a, $b) {
                        return $a->startdate > $b->startdate;
                    });
                } else {
                    usort($filtered, function($a, $b) {
                        return $a->startdate < $b->startdate;
                    });
                }
                break;
        }
        $filtered = array_slice($filtered, $start, $length);
        $courseprogress = array();
        $isteacher = false;
        $courses = [];
        if (count($filtered) != 0) {
            $studentroles = $DB->get_records('role', array('archetype' => 'student'));
            foreach ($filtered as $course) {
                $temp = $this->get_course_progress($course, $studentroles);
                $temp->backColor = 'alternate-row';
                $coursecount += $step;
                $temp->index = $coursecount;
                $courses[] = $temp;
            }
        }
        return array($courses, $totalcourses);
    }

    /**
     * Check if user is teacher in course/courses and return those courses only
     * @param  Integer|Array $courses Single course or course ids
     * @param  Integer       $userid  User id or null if current user
     * @return Integer|Array          True if user is teacher|Array of course ids in which user is enrolled as teacher
     */
    public function user_is_teacher($courses, $userid = null) {
        global $USER, $DB;
        if (is_siteadmin()) {
            return $courses;
        }
        if ($userid == null) {
            $userid = $USER->id;
        }
        $courseidlist = '';
        if (!is_array($courses)) {
            $courses = $courses->id;
        }
        if (empty($courses)) {
            return [];
        }
        list($insql, $inparams) = $DB->get_in_or_equal($courses, SQL_PARAMS_NAMED);
        $params = array_merge(['contextlevel' => CONTEXT_COURSE], $inparams, ['userid' => $userid, 'archetype' => '%teacher%']);
        $sql = "SELECT DISTINCT(ctx.instanceid) id
                  FROM {role_assignments} ra
                  JOIN {context} ctx ON ra.contextid = ctx.id
                  JOIN {role} r ON ra.roleid = r.id
                 WHERE ctx.contextlevel = :contextlevel
                   AND ctx.instanceid $insql
                   AND ra.userid = :userid
                   AND r.archetype LIKE :archetype";
        $result = $DB->get_records_sql($sql, $params);
        if (!is_array($courses)) {
            return $result && reset($result)->id == $courses;
        }
        return array_keys($result);
    }

    /**
     * Get start and end boundry of current page
     *
     * @param  int   $totalcourses Total number of courses found
     * @param  int   $perpage      Courses per page
     * @param  int   $currentpage  Current page number
     * @return array               (start, end) Start and end boundry
     */
    private function courses_start_end($totalcourses, $perpage, $currentpage) {
        if ($totalcourses == 0) {
            return array(0, 0);
        }
        if ($totalcourses <= $perpage) {
            return array(1, $totalcourses);
        }
        $start = $currentpage == 1 ? 1 : ($currentpage - 1) * $perpage + 1;
        $end = $currentpage * $perpage;
        if ($totalcourses < $end) {
            $end = $totalcourses;
        }
        return array($start, $end);
    }

    /**
     * Courses list in which user is enrolled as teacher
     *
     * @param int    $perpage     Number of courses per page
     * @param int    $currentpage Current selected page
     * @param bool   $summary     If true return course summary
     *
     * @return array              (courses, totalcourses, start, end, totalpages)
     */
    public function teacher_manage_courses_data($perpage, $currentpage, $summary = false) {
        global $USER, $CFG, $OUTPUT, $DB, $PAGE;
        $renderer = $PAGE->get_renderer('core');
        // Teacher View Dashboard
        $isadmin = is_siteadmin();
        $filtered = false;
        if ($isadmin) {
            $mycourses = get_courses();
            unset($mycourses[1]);
            $ids = array_keys($mycourses);
        } else {
            $mycourses = $this->get_user_courses();
            $ids = $this->user_is_teacher(array_keys($mycourses), $USER->id);
        }
        $totalcourses = count($ids);
        list($start, $end) = $this->courses_start_end($totalcourses, $perpage, $currentpage);
        $ids = array_slice($ids, $start - 1, $end - ($start - 1));
        $courses = array();
        foreach ($ids as $id) {
            $course = $mycourses[$id];
            if (class_exists('core_course_list_element')) {
                $courseobj = new \core_course_list_element($course);
            } else {
                $courseobj = new \course_in_list($course);
            }
            $courseimage = "";
            foreach ($courseobj->get_course_overviewfiles() as $file) {
                $isimage = $file->is_valid_image();
                if ($isimage) {
                    $courseimage = file_encode_url(
                        "$CFG->wwwroot/pluginfile.php",
                        '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                        $file->get_filearea(). $file->get_filepath(). $file->get_filename(),
                        !$isimage
                    );
                    break;
                }
            }
            if (empty($courseimage)) {
                if (method_exists($renderer, 'get_generated_image_for_id')) {
                    $courseimage = $renderer->get_generated_image_for_id($id);
                } else if (method_exists('\theme_remui\utility', 'get_generated_image_for_id')) {
                    $courseimage = \theme_remui\utility::get_generated_image_for_id($id);
                } else {
                    $courseimage = $OUTPUT->image_url('placeholder', 'theme')->out();
                }
            }
            $courses[] = [
                'id'          => $course->id,
                'fullname'    => strip_tags(format_text($course->fullname)),
                'startdate'   => $course->startdate,
                'courseimage' => $courseimage,
                'summary'     => !$summary ? '' : format_text($DB->get_field(
                    'course',
                    'summary',
                    array('id' => $course->id)
                ))
            ];
        }
        return array($courses, $totalcourses, $start, $end, ceil($totalcourses / $perpage));
    }

    // Function for Teacher Specific Dashboard
    // End

    /* Recent Active Forums*/
    public function get_recent_active_forums() {
        global $CFG;
        $templatecontext['recentforums'] = $this->recent_forum_activity(false, 5);
        if (!empty($templatecontext['recentforums'])) {
            $templatecontext['hasrecentforums'] = 1;
            $templatecontext['discussurl'] = $CFG->wwwroot . '/mod/forum/discuss.php';
            $templatecontext['forumurl'] = $CFG->wwwroot . '/mod/forum/view.php';
            $templatecontext['userurl'] = $CFG->wwwroot . '/user/profile.php';
        } else {
            $templatecontext['hasrecentforums'] = 0;
        }
        return $templatecontext;
    }


    public function recent_forum_activity($userorid = false, $limit = 10, $since = null) {
        global $CFG, $DB;

        if (file_exists($CFG->dirroot.'/mod/hsuforum')) {
            require_once($CFG->dirroot.'/mod/hsuforum/lib.php');
        }
        // call to theme function
        // didn't write this function again in plugin because this function
        // can't be removed from theme as used in many files
        // to avoid redundancy called function from theme as it is
        $user = \theme_remui\utility::get_user($userorid);
        if (!$user) {
            return [];
        }
        if ($since === null) {
            $since = time() - (12 * WEEKSECS);
        }

        // Get all relevant forum ids for SQL in statement.
        // We use the post limit for the number of forums we are interested in too -
        // as they are ordered by most recent post.
        if (file_exists($CFG->dirroot.'/blocks/remuiblck/classes/user_forums.php')) {
            require_once($CFG->dirroot.'/blocks/remuiblck/classes/user_forums.php');
        }
        $userforums = new \block_remuiblck\user_forums($user, $limit);
        $forumids   = $userforums->forumids();

        $sqls = [];
        $params = [];

        if (!empty($forumids)) {
            $forumidsallgroups = $userforums->forumidsallgroups();
            list($finsql, $finparams) = $DB->get_in_or_equal($forumids, SQL_PARAMS_NAMED, 'fina');
            $params = $finparams;
            $params = array_merge(
                $params,
                [
                   'sepgps1a' => SEPARATEGROUPS,
                   'sepgps2a' => SEPARATEGROUPS,
                   'user1a'   => $user->id,
                   'user2a'   => $user->id

                ]
            );

            $fgpsql = '';
            if (!empty($forumidsallgroups)) {
                // Where a forum has a group mode of SEPARATEGROUPS we need a list of those forums where the current
                // user has the ability to access all groups.
                // This will be used in SQL later on to ensure they can see things in any groups.
                list($fgpsql, $fgpparams) = $DB->get_in_or_equal($forumidsallgroups, SQL_PARAMS_NAMED, 'allgpsa');
                $fgpsql = ' OR f1.id '.$fgpsql;
                $params = array_merge($params, $fgpparams);
            }

            $params['user2a'] = $user->id;

            $sqls[] = "(SELECT ".$DB->sql_concat("'F'", 'fp1.id')." AS id, 'forum' AS type, fp1.id AS postid,
                               fd1.forum, fp1.discussion, fp1.parent, fp1.userid, fp1.modified, fp1.subject,
                               fp1.message, 0 AS reveal, cm1.id AS cmid,
                               0 AS forumanonymous, f1.course, f1.name AS forumname,
                               u1.firstnamephonetic, u1.lastnamephonetic, u1.middlename, u1.alternatename, u1.firstname,
                               u1.lastname, u1.picture, u1.imagealt, u1.email,
                               c.shortname AS courseshortname, c.fullname AS coursefullname
                          FROM {forum_posts} fp1
                          JOIN {user} u1 ON u1.id = fp1.userid
                          JOIN {forum_discussions} fd1 ON fd1.id = fp1.discussion
                          JOIN {forum} f1 ON f1.id = fd1.forum AND f1.id $finsql
                          JOIN {course_modules} cm1 ON cm1.instance = f1.id
                          JOIN {modules} m1 ON m1.name = 'forum' AND cm1.module = m1.id
                          JOIN {course} c ON c.id = f1.course
                          LEFT JOIN {groups_members} gm1 ON cm1.groupmode = :sepgps1a
                           AND gm1.groupid = fd1.groupid
                           AND gm1.userid = :user1a
                         WHERE (cm1.groupmode <> :sepgps2a OR (gm1.userid IS NOT NULL $fgpsql))
                          AND fp1.userid <> :user2a
                          AND fp1.modified > $since
                         ORDER BY fp1.modified DESC)";
            // TODO - when moodle gets private reply (anonymous) forums, we need to handle this here.
        }

        $hsuforumids = $userforums->hsuforumids();
        if (!empty($hsuforumids)) {
            list($afinsql, $afinparams) = $DB->get_in_or_equal($hsuforumids, SQL_PARAMS_NAMED, 'finb');
            $params = array_merge($params, $afinparams);
            $params = array_merge(
                $params,
                [
                  'sepgps1b' => SEPARATEGROUPS,
                  'sepgps2b' => SEPARATEGROUPS,
                  'user1b'   => $user->id,
                  'user2b'   => $user->id,
                  'user3b'   => $user->id,
                  'user4b'   => $user->id
                ]
            );

            $afgpsql = '';
            $hsuforumidsallgroups = $userforums->hsuforumidsallgroups();
            if (!empty($hsuforumidsallgroups)) {
                // Where a forum has a group mode of SEPARATEGROUPS we need a list of those forums where the current
                // user has the ability to access all groups.
                // This will be used in SQL later on to ensure they can see things in any groups.
                list($afgpsql, $afgpparams) = $DB->get_in_or_equal($hsuforumidsallgroups, SQL_PARAMS_NAMED, 'allgpsb');
                $afgpsql = ' OR f2.id '.$afgpsql;
                $params = array_merge($params, $afgpparams);
            }

            $sqls[] = "(SELECT ".$DB->sql_concat("'A'", 'fp2.id')." AS id, 'hsuforum' AS type, fp2.id AS postid,
                               fd2.forum, fp2.discussion, fp2.parent, fp2.userid, fp2.modified, fp2.subject,
                               fp2.message, fp2.reveal, cm2.id AS cmid,
                               f2.anonymous AS forumanonymous, f2.course, f2.name AS forumname,
                               u2.firstnamephonetic, u2.lastnamephonetic, u2.middlename, u2.alternatename, u2.firstname,
                               u2.lastname, u2.picture, u2.imagealt, u2.email,
                               c.shortname AS courseshortname, c.fullname AS coursefullname
                          FROM {hsuforum_posts} fp2
                          JOIN {user} u2 ON u2.id = fp2.userid
                          JOIN {hsuforum_discussions} fd2 ON fd2.id = fp2.discussion
                          JOIN {hsuforum} f2 ON f2.id = fd2.forum AND f2.id $afinsql
                          JOIN {course_modules} cm2 ON cm2.instance = f2.id
                          JOIN {modules} m2 ON m2.name = 'hsuforum' AND cm2.module = m2.id
                          JOIN {course} c ON c.id = f2.course
                          LEFT JOIN {groups_members} gm2 ON cm2.groupmode = :sepgps1b
                           AND gm2.groupid = fd2.groupid
                           AND gm2.userid = :user1b
                         WHERE (cm2.groupmode <> :sepgps2b OR (gm2.userid IS NOT NULL $afgpsql))
                           AND (fp2.privatereply = 0 OR fp2.privatereply = :user2b OR fp2.userid = :user3b)
                           AND fp2.userid <> :user4b
                           AND fp2.modified > $since
                         ORDER BY fp2.modified DESC)";
        }

        if (empty($forumids) && empty($hsuforumids)) {
            return [];
        }

        $sql = '-- remui sql'. "\n".implode("\n".' UNION ALL '."\n", $sqls);
        if (count($sqls) > 1) {
            $sql .= "\n".' ORDER BY modified DESC';
        }
        $posts = $DB->get_records_sql($sql, $params, 0, $limit);

        $activities = [];

        $topics = [];
        $count = -1;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $postuser = (object)[
                    'id' => $post->userid,
                    'firstnamephonetic' => $post->firstnamephonetic,
                    'lastnamephonetic' => $post->lastnamephonetic,
                    'middlename' => $post->middlename,
                    'alternatename' => $post->alternatename,
                    'firstname' => $post->firstname,
                    'lastname' => $post->lastname,
                    'picture' => $post->picture,
                    'imagealt' => $post->imagealt,
                    'email' => $post->email
                ];

                // Update the user object with user profile photo
                $postuser->profilepicture = \theme_remui\utility::get_user_picture($postuser, 15)->out();

                if ($post->type === 'hsuforum') {
                    $postuser = hsuforum_anonymize_user($postuser, (object)array(
                        'id' => $post->forum,
                        'course' => $post->course,
                        'anonymous' => $post->forumanonymous
                    ), $post);
                }

                if (!in_array($post->cmid, $topics)) {
                    $activities[] = (object)[
                        'type' => $post->type,
                        'cmid' => $post->cmid,
                        'name' => $post->subject,
                        'courseshortname' => $post->courseshortname,
                        'coursefullname' => $post->coursefullname,
                        'forumname' => $post->forumname,
                        'sectionnum' => null,
                        'timestamp' => $post->modified,
                        'content' => (object)[
                            'id' => $post->postid,
                            'discussion' => $post->discussion,
                            'subject' => $post->subject,
                            'parent' => $post->parent
                        ],
                        'user' => $postuser
                    ];
                    $topics[] = $post->cmid;
                    $count++;
                    $activities[$count]->replies = 1;
                    $activities[$count]->recentuser = [$postuser];
                } else {
                    $activities[$count]->replies = $activities[$count]->replies + 1;
                    if (!in_array($postuser, $activities[$count]->recentuser) && count($activities[$count]->recentuser) <= 2) {
                        array_push($activities[$count]->recentuser, $postuser);
                    }
                }
            }
        }
        return $activities;
    }


    /*Get recent Assignment*/
    public function get_recent_assignment() {
        global $USER;

        $data = array();
        $templatecontext = array();
        $chelper = new \coursecat_helper();
        $recentassignments = \theme_remui\utility::grading();
        if ($recentassignments) {
            $templatecontext['hasrecentassignments'] = true;
            $count = 0;
            foreach ($recentassignments as $ungraded) {
                $modinfo = get_fast_modinfo($ungraded->course);
                $course = $modinfo->get_course();
                $cm = $modinfo->get_cm($ungraded->coursemoduleid);
                $data[$count] = new \stdClass;
                $data[$count]->cm_url = $cm->url->out();
                $data[$count]->cm_name = $cm->name;
                $data[$count]->course_fullname = strip_tags($chelper->get_course_formatted_name($course));
            }
            $templatecontext['recentassignments'] = $data;
        } else {
            $grades = \theme_remui\utility::graded();
            if (!empty($grades)) {
                $templatecontext['hasrecentfeedback'] = true;
                $count = 0;
                foreach ($grades as $grade) {
                    $modinfo = get_fast_modinfo($grade->courseid);
                    $course = $modinfo->get_course();

                    $modtype = $grade->itemmodule;
                    $cm = $modinfo->instances[$modtype][$grade->iteminstance];

                    $coursecontext = \context_course::instance($grade->courseid);
                    $canviewhiddengrade = has_capability('moodle/grade:viewhidden', $coursecontext);

                    $grade = new \grade_grade(array('itemid' => $grade->itemid, 'userid' => $USER->id));
                    if (!$grade->is_hidden() || $canviewhiddengrade) {
                        $data[$count] = new \stdClass;
                        $data[$count]->courseurl = (new moodle_url('/course/view.php?id=' . $grade->grade_item->courseid))->out();
                        $data[$count]->course_shortname = $course->shortname;
                        $data[$count]->assignurl = $cm->url->out();
                        $data[$count]->grade_itemname = $grade->grade_item->itemname;
                        $data[$count]->grade_rawgrade = intval($grade->rawgrade);
                        $data[$count]->grade_rawgrademax = intval($grade->rawgrademax);
                        $data[$count]->timemodified = $grade->timemodified;
                    }
                }
                $templatecontext['recentfeedback'] = $data;
            }
        }
        return $templatecontext;
    }

    /**
     * Returns an array of activities (defined as $cm objects) which are gradeable from gradebook, outcomes are ignored.
     *
     * @param int $courseid If provided then restrict to one course.
     * @param string $modulename If defined (could be 'forum', 'assignment' etc) then only that type are returned.
     * @return array $cm objects
     */
    private function grade_get_gradable_activities($courseids, $modulename='') {
        global $DB;
        if (empty($courseids)) {
            return [];
        }
        $params = array(GRADE_TYPE_NONE);
        $sql = "SELECT DISTINCT(gi.courseid) id
                  FROM {grade_items} gi, {course_modules} cm, {modules} md
                 WHERE gi.courseid IN (".implode(',', $courseids).")
                   AND gi.itemtype = 'mod'
                   AND gi.itemnumber = 0
                   AND gi.gradetype != ?
                   AND gi.iteminstance = cm.instance
                   AND md.visible = 1
                   AND md.id = cm.module";
        return $DB->get_records_sql($sql, $params);
    }

    public function get_analytics_overview() {
        global $USER;
        // analytics_overview
        $chelper = new \coursecat_helper();
        $courses = $this->get_user_courses();
        $gradable = $this->grade_get_gradable_activities(array_keys($courses));
        $qcourse = [];
        if (!empty($gradable)) {
            foreach ($gradable as $course) {
                $course->fullname = strip_tags($chelper->get_course_formatted_name($courses[$course->id]));
                $qcourse[] = ['id' => $course->id, 'name' => $course->fullname];
            }
        }
        $templatecontext['quizcourse'] = $qcourse;
        if (count($qcourse)) {
            $templatecontext['hasanalytics'] = 1;
        } else {
            $templatecontext['hasanalytics'] = 0;
        }
        return $templatecontext;
    }

    /**
     * Get course statistic
     *
     * @param object $course course object
     *
     * @return array course statistic array
     */
    public function get_course_stats($course) {
        global $DB;
        $stats = array();
        $enrolledusers = $DB->get_records_sql(
            "SELECT u.*
               FROM {course} c
               JOIN {context} ctx ON c.id = ctx.instanceid AND ctx.contextlevel = ?
               JOIN {enrol} e ON c.id = e.courseid
               JOIN {user_enrolments} ue ON e.id = ue.enrolid
               JOIN {user} u ON ue.userid = u.id
               JOIN {role_assignments} ra ON ctx.id = ra.contextid AND u.id = ra.userid AND ra.roleid = ?
              WHERE c.id = ?",
            array(CONTEXT_COURSE, 5, $course->id)
        );
        $stats['enrolledusers'] = count($enrolledusers);

        $completion = new \completion_info($course);
        if ($completion->is_enabled()) {
            $inprogress = 0;
            $studentcompleted = 0;
            $yettostart = 0;
            $modules = $completion->get_activities();
            foreach ($enrolledusers as $user) {
                $activitiesprogress = 0;
                foreach ($modules as $module) {
                    $moduledata = $completion->get_data($module, false, $user->id);
                    $activitiesprogress += $moduledata->completionstate == COMPLETION_INCOMPLETE ? 0 : 1;
                }
                if ($activitiesprogress == 0) {
                    $yettostart++;
                } else if ($activitiesprogress == count($modules)) {
                    $studentcompleted++;
                } else {
                    $inprogress++;
                }
            }
            $stats['nocompletion'] = false;
            $stats['studentcompleted'] = $studentcompleted;
            $stats['inprogress'] = $inprogress;
            $stats['yettostart'] = $yettostart;
        } else {
            $stats['nocompletion'] = true;
        }
        return $stats;
    }

    /**
     * Get statistic of users based on filter
     *
     * @param int $courseid id of course
     * @param string $search search query
     * @param bool $table does this call is from table or export button
     * @param int $start start index of record based on pagination
     * @param int $length number of records per page
     * @param array $order [column => id of sorting column
     *                     dir => direction of sorting eigther asc or desc]
     *
     * @return array users statistic array
     */
    public function get_filtered_dropping_user_stats($courseid, $search, $table, $start, $length, $order) {
        global $DB;
        $columns = array("Name", "u.email", "ue.timestart", "lsl.timecreated");
        $column = $columns[$order['column']];
        $dir = $order['dir'];
        $sql = "SELECT u.id, u.picture, u.firstname, u.lastname, u.firstnamephonetic, u.lastnamephonetic,
                       u.middlename, u.alternatename, u.imagealt, u.email, u.email,
                       CONCAT(u.firstname, ' ', u.lastname) 'Name', ue.timestart, lsl.timecreated
                  FROM {course} c
                  JOIN {context} ctx ON c.id = ctx.instanceid AND ctx.contextlevel = :contextlevel
                  JOIN {enrol} e ON c.id = e.courseid
                  JOIN {user_enrolments} ue ON e.id = ue.enrolid
                  JOIN {user} u ON ue.userid = u.id
                  JOIN {role_assignments} ra ON ctx.id = ra.contextid AND u.id = ra.userid AND ra.roleid = :studentrole
                  LEFT JOIN(SELECT courseid, userid, MAX(timecreated) timecreated
                              FROM {logstore_standard_log}
                             WHERE eventname LIKE :eventname OR eventname is null
                             GROUP BY courseid, userid) lsl ON ctx.instanceid = lsl.courseid AND u.id = lsl.userid
                 WHERE c.id = :courseid";
        $params = array(
            "contextlevel" => CONTEXT_COURSE,
            "studentrole"  => 5,
            "courseid"     => $courseid,
            "eventname"    => "%course_viewed%"
        );
        $sql .= " GROUP BY u.id, ue.timestart";
        if ($search != '') {
            $sql .= " HAVING Name LIKE :namefilter OR u.email LIKE :emailfilter";
            $params["namefilter"] = "%$search%";
            $params["emailfilter"] = "%$search%";
        }
        if ($table) {
            $sql .= " ORDER BY $column $dir";
            $sql .= " LIMIT $start, $length";
        }
        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Get dropping user statistic count with filter paramters
     *
     * @param int $courseid id of course
     * @param string $search search query
     *
     * @return array users records
     */
    public function get_filtered_dropping_user_stats_count($courseid, $search) {
        global $DB;
        $sql = "SELECT count(u.id)
                  FROM {course} c
                  JOIN {context} ctx ON c.id = ctx.instanceid AND ctx.contextlevel = :contextlevel
                  JOIN {enrol} e ON c.id = e.courseid
                  JOIN {user_enrolments} ue ON e.id = ue.enrolid
                  JOIN {user} u ON ue.userid = u.id
                  JOIN {role_assignments} ra ON ctx.id = ra.contextid AND u.id = ra.userid AND ra.roleid = :studentrole
                 WHERE c.id = :courseid";
        $params = array(
            "contextlevel" => CONTEXT_COURSE,
            "studentrole"  => 5,
            "courseid"     => $courseid
        );
        if ($search != '') {
            $sql .= " AND CONCAT(u.firstname, ' ', u.lastname) LIKE :namefilter OR u.email LIKE :emailfilter";
            $params["namefilter"] = "%$search%";
            $params["emailfilter"] = "%$search%";
        }
        return $DB->count_records_sql($sql, $params);
    }

    /**
     * Get user image and link for course
     *
     * @param stdClass $user user object
     * @param int $courseid id of course
     *
     * @return string user image and link in html format
     */
    public function get_user_image_and_link($user, $courseid) {
        $title = get_string('pictureof', 'moodle', "$user->firstname $user->lastname");
        $imagelink = html_writer::tag(
            'img',
            '',
            array(
                'src' => \theme_remui\utility::get_user_picture($user),
                'alt' => $title,
                'title' => $title,
                'class' => 'userpicture',
                'width' => 35,
                'height' => 35
            )
        ) . "$user->firstname $user->lastname";
        $userprofileurl = new moodle_url(
            '/user/view.php',
            array('id' => $user->id, 'course' => $courseid)
        );
        return html_writer::tag(
            'a',
            $imagelink,
            array(
                'href' => $userprofileurl,
                'target' => '_blank',
                'remui-block-manage-course-profile-link'
            )
        );
    }

    /**
     * Get last course access time
     * @param  int    $courseid  Course id
     * @param  int    $studentid Student id
     * @return string            Last access time
     */
    public function get_last_course_access_time($courseid, $studentid) {
        global $DB;
        $lastaccess = new stdClass;

        $lastaccess->time = 'Never Accessed';
        $lastaccess->class = 'text-danger';

        $currtime = time();

        $record = $DB->get_field('user_lastaccess', 'timeaccess', array('userid' => $studentid, 'courseid' => $courseid), IGNORE_MISSING);

        if (!empty($record)) {
            $lastaccess->time = 'Last Active on ' . date("d M, Y", substr($record, 0, 10));
            $lastaccess->class = ($currtime - $record) > 259200 ? 'text-danger' : (($currtime - $record) > 129600 ? 'text-warning' : 'text-success');
        }
        return $lastaccess;
    }
}
