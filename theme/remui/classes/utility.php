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

use moodle_url;
use user_picture;
use context_system;
use context_course;
use core_completion\progress;
use theme_remui\toolbox as toolbox;
use core_course_list_element;

class utility {

    /**
     * Get user picture from user object
     * @param  object  $userobject User object
     * @param  integer $imgsize    Size of image in pixel
     * @return String              User picture link
     */
    public static function get_user_picture($userobject = null, $imgsize = 100) {
        global $USER, $PAGE;
        if (!$userobject) {
            $userobject = $USER;
        }

        $userimg = new user_picture($userobject);
        $userimg->size = $imgsize;
        return  $userimg->get_url($PAGE);
    }

    /**
     * Returns left navigation footer menus details.
     *
     * @return Array Menu details.
     */
    public static function get_left_nav_footer_menus() {
        global $CFG, $COURSE, $USER;
        $menudata = array (
            [
                'url' => $CFG->wwwroot.'/course/index.php',
                'iconclass' => 'fa-archive',
                'title' => get_string('createarchivepage', 'theme_remui')
            ]
        );
        // Return all menus for site administrator.
        if (is_siteadmin($USER)) {
            $menus = array (
                [
                    'url' => "{$CFG->wwwroot}/{$CFG->admin}/user.php",
                    'iconclass' => 'fa-users',
                    'title' => get_string('userlist')
                ],
                [
                    'url' => self::get_course_creation_link(),
                    'iconclass' => 'fa-file',
                    'title' => get_string('createanewcourse', 'theme_remui')
                ],
                [
                    'url' => "{$CFG->wwwroot}/{$CFG->admin}/settings.php?section=themesettingremui",
                    'iconclass' => 'fa-cogs',
                    'title' => get_string('remuisettings', 'theme_remui')
                ]
            );
            $menudata = array_merge($menudata, $menus);
            $temp = array_splice($menudata, 1, 1);
            array_splice($menudata, 0, 0, $temp);
            return $menudata;
        }

        // Return menus for course creator.
        $coursecontext = context_course::instance($COURSE->id);
        if (has_capability('moodle/course:create', $coursecontext)) {
            $menu = [
                'url' => self::get_course_creation_link(),
                'iconclass' => 'fa-file',
                'title' => get_string('createanewcourse', 'theme_remui')
            ];
            array_push($menudata, $menu);
            $temp = array_splice($menudata, 1, 1);
            array_splice($menudata, 0, 0, $temp);
            return $menudata;
        }
        return $menudata;
    }

    /**
     * Returns the link for creating new course.
     *
     * @return string Course creation link.
     */
    public static function get_course_creation_link() {
        global $DB, $CFG;
        $categories = $DB->get_records('course_categories', null, '', 'id');
        $createcourselink = "#";
        if (!empty($categories)) {
            $firstcategory = reset($categories);
            $createcourselink = $CFG->wwwroot. '/course/edit.php?category='.$firstcategory->id;
        }
        return $createcourselink;
    }

    /**
     * Return the recent blog.
     *
     * This function helps in retrieving the recent blog.
     *
     * @param int $start how many blog should be skipped if specified 0 no recent blog will be skipped.
     * @param int $blogcount number of blog to be return.
     * @param string $filearea file area
     * @return array $blog returns array of blog data.
     */

    public static function get_recent_blogs($start = 0, $blogcount = 10) {
        global $CFG;

        require_once($CFG->dirroot.'/blog/locallib.php');
        $bloglisting = new \blog_listing();

        $blogentries = $bloglisting->get_entries($start, $blogcount);

        foreach ($blogentries as $blogentry) {
            $blogsummary = strip_tags($blogentry->summary);
            $summarystring = strlen($blogsummary) > 150 ? substr($blogsummary, 0, 150)."..." : $blogsummary;
            $blogentry->summary = $summarystring;

            // Created at.
            $blogentry->createdat = date('d M, Y', $blogentry->created);

            // Link.
            $blogentry->link = $CFG->wwwroot.'/blog/index.php?entryid='.$blogentry->id;
        }
        return $blogentries;
    }

    /**
     * This function is used to get the data for either slider or static at a time.
     *
     * @return array of sliding data
     */
    public static function get_slider_data() {
        global $PAGE, $OUTPUT;

        $sliderdata = array();
        $sliderdata['isslider'] = false;
        $sliderdata['isimage']  = false;
        $sliderdata['isvideo']  = false;
        $sliderdata['slideinterval'] = false;

        if (toolbox::get_setting('sliderautoplay') == '1') {
            $sliderdata['slideinterval'] = toolbox::get_setting('slideinterval');
        }

        $numberofslides = toolbox::get_setting('slidercount');

        // Get the content details either static or slider.
        $frontpagecontenttype = toolbox::get_setting('frontpageimagecontent');

        if ($frontpagecontenttype) { // Dynamic image slider.
            $sliderdata['isslider'] = true;
            if ($numberofslides >= 1) {
                for ($count = 1; $count <= $numberofslides; $count++) {
                    $sliderimageurl = toolbox::setting_file_url('slideimage'.$count, 'slideimage'.$count);
                    if ($sliderimageurl == "" || $sliderimageurl == null) {
                        $sliderimageurl = toolbox::image_url('slide', 'theme');
                    }
                    $sliderimagetext = format_text(toolbox::get_setting('slidertext'.$count));
                    $sliderimagelink = toolbox::get_setting('sliderurl'.$count);
                    $sliderbuttontext = toolbox::get_setting('sliderbuttontext'.$count);
                    if ($count == 1) {
                        $active = true;
                    } else {
                        $active = false;
                    }
                    $sliderdata['slides'][] = array(
                    'img' => $sliderimageurl,
                    'img_txt' => $sliderimagetext,
                    'btn_link' => $sliderimagelink,
                    'btn_txt' => $sliderbuttontext,
                    'active' => $active,
                    'count' => $count - 1);
                }
            }
        } else if (!$frontpagecontenttype) { // Static data.
            // Get the static front page settings.
            $sliderdata['addtxt'] = format_text(toolbox::get_setting('addtext'));

            $contenttype = toolbox::get_setting('contenttype');
            if (!$contenttype) {
                $sliderdata['isvideo'] = true;
                $url = toolbox::get_setting('video');
                $sliderdata['video'] = $url == '' ? 'https://www.youtube.com/embed/wop3FMhoLGs' : $url;
                $sliderdata['videoalignment'] = toolbox::get_setting('frontpagevideoalignment');
            } else if ($contenttype) {
                $sliderdata['isimage'] = true;
                $staticimage = toolbox::setting_file_url('staticimage', 'staticimage');
                if ($staticimage == "" || $staticimage == null) {
                    $sliderdata['staticimage'] = toolbox::image_url('slide', 'theme');
                } else {
                    $sliderdata['staticimage'] = $staticimage;
                }
            }
        }
        return $sliderdata;
    }

    /**
     * This function is used to get the data for testimonials in about us section.
     *
     * @return array of testimonial data
     */
    public static function get_testimonial_data() {
        global $PAGE, $OUTPUT;

        // Return if acout us is disabled.
        if (!toolbox::get_setting('enablefrontpageaboutus')) {
            return false;
        }

        $testimonialdata = array(
                'both' => false,
                'about' => false,
                'test' => false
            );
        $testimonialcount = toolbox::get_setting('testimonialcount');

        if ($testimonialcount >= 1) {
            $testimonialdata['test'] = true;

            for ($count = 1; $count <= $testimonialcount; $count++) {
                $testimonialimageurl = toolbox::setting_file_url('testimonialimage'.$count, 'testimonialimage'.$count);

                $testimonialname = toolbox::get_setting('testimonialname'.$count);
                $testimonialdesignation = toolbox::get_setting('testimonialdesignation'.$count);
                $testimonialtext = toolbox::get_setting('testimonialtext'.$count);
                if ($count == 1) {
                    $active = true;
                } else {
                    $active = false;
                }
                $testimonialdata['testimonials'][] = array(
                'image' => @$testimonialimageurl,
                'name' => $testimonialname,
                'designation' => $testimonialdesignation,
                'text' => $testimonialtext,
                'active' => $active,
                'count' => $count - 1);
            }
        }

        // About us data.
        $testimonialdata['aboutus_heading'] = toolbox::get_setting('frontpageaboutusheading');
        $testimonialdata['aboutus_desc'] = toolbox::get_setting('frontpageaboutustext');

        if (!empty($testimonialdata['aboutus_heading']) || !empty($testimonialdata['aboutus_desc'])) {
            $testimonialdata['about'] = true;
        }
        if ($testimonialdata['test'] && $testimonialdata['about']) {
            $testimonialdata['both'] = true;
        }

        return $testimonialdata;
    }

    /**
     * Check user is admin or manager
     * @param  object  $userobject User object
     * @return boolean             True if admin or manager
     */
    public static function check_user_admin_cap($userobject = null) {
        global $USER;
        if (!$userobject) {
            $userobject = $USER;
        }
        if (is_siteadmin()) {
            return true;
        }
        $context = context_system::instance();
        $roles = get_user_roles($context, $userobject->id, false);
        foreach ($roles as $role) {
            if ($role->roleid == 1 && $role->shortname == 'manager') {
                return true;
            }
        }
        return false;
    }

    /**
     * Return user's courses or all the courses
     *
     * Usually called to get usr's courese, or it could also be called to get all course.
     * This function will also be called whern search course is used.
     *
     * @param string $search course name to be search
     * @param int $category ids to be search of courses.
     * @param int $usercourses to return user's course which he/she enrolled into.
     * @param int $limitfrom course to be returned from these number onwards, like from course 5 .
     * @param int $limitto till this number course to be returned , like from course 10, then 5 course will be returned from 5 to 10.
     * @param int $showhidden include hidden courses in results.
     * @return array of course.
     */
    public static function get_courses(
        $totalcount = false,
        $search = null,
        $category = null,
        $limitfrom = 0,
        $limitto = 0,
        $mycourses = null,
        $categorysort = null,
        $courses = [],
        $filtermodified = true
    ) {
        $coursehandler = new \theme_remui_coursehandler();
        return $coursehandler->get_courses(
            $totalcount,
            $search,
            $category,
            $limitfrom,
            $limitto,
            $mycourses,
            $categorysort,
            $courses,
            $filtermodified
        );

    }

    /**
     * Get card content for courses
     * @param  object $wdmdata Data to create cards
     * @param  string $date    Date filter
     * @return array           Courses cards
     */
    public static function get_course_cards_content($wdmdata, $date = 'all') {
        global $CFG, $OUTPUT;
        $courseperpage = \theme_remui\toolbox::get_setting('courseperpage');
        $categorysort = $wdmdata->sort;
        $search       = $wdmdata->search;
        $category     = $wdmdata->category;
        $courses      = isset($wdmdata->courses) ? $wdmdata->courses : [];
        $mycourses    = $wdmdata->tab;
        $page         = ($mycourses) ? $wdmdata->page->mycourses : $wdmdata->page->courses;
        $startfrom    = $courseperpage * $page;
        $limitto      = $courseperpage;
        $filtermodified = isset($wdmdata->isFilterModified) ? $wdmdata->isFilterModified : true;
        $allowfull = true;
        // Resultant Array.
        $result = array();

        if ($page == -1) {
            $startfrom = 0;
            $limitto = 0;
        }

        // This condition is for coursecategory page only, that is why on frontpage it is not
        // necessary so returning limiteddata.
        if (isset($wdmdata->limiteddata)) {
            $allowfull = false;
        }

        // Pagination Context creation.
        if ($wdmdata->pagination) {
            // first paremeter true means get_courses function will return count of the result and if false, returns actual data.
            list($totalcourses, $courses)  = self::get_courses(2, $search, $category, $startfrom, $limitto, $mycourses, $categorysort, $courses, $filtermodified);

            $pagingbar  = new \paging_bar($totalcourses, $page, $courseperpage, 'javascript:void(0);', 'page');
            $result['pagination'] = $OUTPUT->render($pagingbar);
        } else {
            // Fetch the courses.
            $courses = self::get_courses(false, $search, $category, $startfrom, $limitto, $mycourses, $categorysort, $courses, $filtermodified);
        }

        // Courses Data.
        $coursecontext = array();
        foreach ($courses as $key => $course) {
            if ($date != 'all') {
                // Get the current time value.
                $time = new \DateTime("now", \core_date::get_user_timezone_object());
                $time->add(new \DateInterval("P1D"));

                $timestamp = $time->getTimestamp();

                // Check if inprogress and not passed the course end date.
                if ($date == 'inprogress' && $timestamp < $course['epochenddate']) {
                    continue;
                }

                // Check if future and not passed course start date.
                if ($date == 'future' && $timestamp > $course['epochstartdate']) {
                    continue;
                }

                // Check if past and not passed end date.
                if ($date == 'past' && $timestamp < $course['epochenddate']) {
                    continue;
                }
            }
            $coursedata = array();
            $coursedata['id'] = $course['courseid'];
            $coursedata['grader']    = $course['grader'];
            $coursedata['shortname'] = strip_tags(format_text($course['shortname']));
            $coursedata['courseurl'] = $course['courseurl'];
            $coursedata['coursename'] = strip_tags(format_text($course['coursename']));
            $coursedata['enrollusers'] = $course['enrollusers'];
            $coursedata['editcourse']  = $course['editcourse'];
            $coursedata['activity']    = $course['activity'];
            $coursedata['categoryname'] = format_text($course['categoryname']);
            if ($course['visible']) {
                $coursedata['visible'] = $course['visible'];
            }

            // This condition to handle the string url or moodle_url object problem.
            if (is_object($course['courseimage'])) {
                $coursedata['courseimage'] = $course['courseimage']->__toString();
            } else {
                $coursedata['courseimage'] = $course['courseimage'];
            }
            $coursedata['coursesummary'] = $course['coursesummary'];
            if (isset($course['coursestartdate'])) {
                $coursedata['startdate']['day'] = substr($course['coursestartdate'], 0, 2);
                $coursedata['startdate']['month'] = substr($course['coursestartdate'], 3, 3);
                $coursedata['startdate']['year'] = substr($course['coursestartdate'], 8, 4);
            }
            // Course card - Footer context is different for mycourses and all courses tab.
            if ($mycourses) {
                // Context creation for mycourses.
                $coursedata['mycourses'] = true;
                if (isset($course['coursecompleted'])) {
                    $coursedata["coursecompleted"] = $course['coursecompleted'];
                }
                if (isset($course['courseinprogress'])) {
                    $coursedata["courseinprogress"] = $course['courseinprogress'];
                    $coursedata["percentage"] = $course['percentage'];
                }
                if (isset($course['coursetostart'])) {
                    $coursedata["coursetostart"] = $course['coursetostart'];
                }
            } else {
                // Context creation for all courses.
                if (isset($course['usercanmanage']) && $allowfull) {
                    $coursedata["usercanmanage"] = $course['usercanmanage'];
                }

                if (isset($course['enrollmenticons']) && $allowfull) {
                    $coursedata["enrollmenticons"] = $course['enrollmenticons'];
                }
            }

            if (isset($course['instructors']) && $allowfull) {
                $instructors = array();
                foreach ($course['instructors'] as $key2 => $instructor) {
                    $instructordetail['name'] = $instructor['name'];
                    $instructordetail['url'] = $instructor['url'];
                    $instructordetail['picture'] = $instructor['picture']->__toString();
                    $instructors[] = $instructordetail;
                }
                $coursedata['instructors'] = $instructors;
            }

            if ($allowfull) {
                $coursedata['widthclasses'] = 'col-lg-3 col-sm-12 col-md-6';
            } else {
                $coursedata['widthclasses'] = 'col-12 h-p100 ';
            }

            // Animation Setting courseanimation.
            $coursedata['animation'] = \theme_remui\toolbox::get_setting('courseanimation');
            if (!\theme_remui\toolbox::get_setting('enablenewcoursecards')) {
                $coursedata['old_card'] = true;
            }
            $coursecontext[] = $coursedata;
        }
        $result['courses'] = $coursecontext;
        $result['view'] = get_user_preferences('course_view_state');

        if (\theme_remui\toolbox::get_setting('enablenewcoursecards')) {
            $result['latest_card'] = true;
        }

        return $result;
    }

    /**
     * Returns the custom activity navigation content.
     *
     * @return void
     */
    public static function get_activity_list() {
        global $COURSE, $PAGE;

        // Return nothing if no cmid.
        if (!isset($PAGE->cm->id)) {
            return;
        }

        $modinfo = get_fast_modinfo($COURSE);
        $sectionsdata = $modinfo->sections;
        $excludedmods = array('label');
        $count = 0; // To print section count in sidebar.
        $courserenderer = $PAGE->get_renderer('core', 'course');
        $sections = array();

        foreach ($modinfo->get_section_info_all() as $mod => $value) {
            // Return if sections does not have activities or section is hidden to current user.
            if (!array_key_exists($mod, $modinfo->sections) || !$value->uservisible) {
                continue;
            }
            $sectionname = get_section_name($COURSE, $value);

            // Check if current section is being viewed.
            $opensection = '';
            if (in_array($PAGE->cm->id, $sectionsdata[$mod])) {
                $opensection = 'show';
            }

            $sections[$count]['name'] = $sectionname;
            $sections[$count]['open'] = $opensection;
            $sections[$count]['count'] = $count + 1;

            // Activities.
            foreach ($sectionsdata[$mod] as $activityid) {
                $activity = $modinfo->get_cm($activityid);

                $classes = '';
                $completioninfo = new \completion_info($COURSE);
                $activitycompletion = $courserenderer->course_section_cm_completion($COURSE, $completioninfo, $activity, array());

                if (!in_array($activity->modname, $excludedmods)) {
                    // Check if current activity.
                    $active = ' ';
                    if ($PAGE->cm->id == $activityid) {
                        $active = 'active ';
                    }

                    $completion = $completioninfo->is_enabled($activity);
                    if ($completion == COMPLETION_TRACKING_NONE) {
                        $classes = '';
                    } else {
                        $completiondata = $completioninfo->get_data($activity, true);
                        switch ($completiondata->completionstate) {
                            case COMPLETION_INCOMPLETE:
                                $classes = 'incomplete';
                                break;
                            case COMPLETION_COMPLETE:
                                $classes = 'complete';
                                break;
                            case COMPLETION_COMPLETE_PASS:
                                $classes = 'complete';
                                break;
                            case COMPLETION_COMPLETE_FAIL:
                                $classes = 'fail';
                                break;
                        }
                    }
                    $sections[$count]['activity_list'][] = array(
                        'active' => $active,
                        'name' => $activity->name,
                        'title'  => $courserenderer->course_section_cm_name_title($activity, array()),
                        'classes' => $classes
                    );
                }
            }
            $count++;
        }

        return $sections;
    }

    /**
     * Set all the child categories of parent category
     *
     * @param  integer $category Category id
     *
     * @return array             Child categories
     */
    public static function get_children_category($category) {
        global $DB;
        $childs = [$DB->get_record('course_categories', array('id' => $category))];
        $childcategories = $DB->get_records_sql('SELECT * FROM {course_categories} WHERE parent = ?', array($category));
        if (!empty($childcategories)) {
            foreach ($childcategories as $child) {
                $childs = array_merge($childs, self::get_children_category($child->id));
            }
        }
        return $childs;
    }

    /**
     * Get allowed categories from category id
     *
     * @param  integer $categoryid Category id
     *
     * @return array               Category ids
     */
    public static function get_allowed_categories($categoryid, $userid = null) {
        global $DB, $USER;

        if ($userid == null) {
            $userid = $USER->id;
        }

        $allcats = array_keys(\core_course_category::make_categories_list());
        $allowedcat = array();

        if ($categoryid == 'all') {
            return $allcats;
        } else if ($categoryid !== null && is_numeric($categoryid)) {
            $sql = "SELECT * FROM {course_categories} WHERE path LIKE ? OR path LIKE ?";
            $categories = $DB->get_records_sql($sql, array('%/' . $categoryid, '%/' . $categoryid . '/%'));
            $allowedcat = array_intersect($allcats, array_keys($categories));
        }

        return $allowedcat;
    }


    /**
     * Sort function for ungraded items in the teachers personal menu.
     *
     * Compare on closetime, but fall back to openening time if not present.
     * Finally, sort by unique coursemodule id when the dates match.
     *
     * @return int
     */
    public static function sort_graded($left, $right) {
        if (empty($left->closetime)) {
            $lefttime = $left->opentime;
        } else {
            $lefttime = $left->closetime;
        }

        if (empty($right->closetime)) {
            $righttime = $right->opentime;
        } else {
            $righttime = $right->closetime;
        }

        if ($lefttime === $righttime) {
            if ($left->coursemoduleid === $right->coursemoduleid) {
                return 0;
            } else if ($left->coursemoduleid < $right->coursemoduleid) {
                return -1;
            } else {
                return 1;
            }
        } else if ($lefttime < $righttime) {
            return  -1;
        } else {
            return 1;
        }
    }

    /**
     * Get items which have been graded.
     *
     * @param bool $onlyactive - only show grades in courses actively enrolled on if true.
     * @return string
     * @throws \coding_exception
     */
    public static function graded() {
        $grades = self::events_graded();
        return $grades;
    }

    public static function grading() {
        global $USER, $PAGE;

        $grading = self::all_ungraded($USER->id);
        return $grading;
    }

    /**
     * Get everything graded from a specific date to the current date.
     *
     * @param bool $onlyactive - only show grades in courses actively enrolled on if true.
     * @param null|int $showfrom - timestamp to show grades from. Note if not set defaults to 1 month ago.
     * @return mixed
     */
    public static function events_graded() {
        global $DB, $USER;

        $params = [];
        $coursesql = '';
        $courses = enrol_get_my_courses();
        $courseids = array_keys($courses);
        $courseids[] = SITEID;
        list($coursesql, $params) = $DB->get_in_or_equal($courseids);
        $coursesql = 'AND gi.courseid '.$coursesql;

        $onemonthago = time() - (DAYSECS * 31);
        $showfrom = $onemonthago;

        $sql = "SELECT gg.*, gi.itemmodule, gi.iteminstance, gi.courseid, gi.itemtype
                  FROM {grade_grades} gg
                  JOIN {grade_items} gi
                    ON gg.itemid = gi.id $coursesql
                 WHERE gg.userid = ?
                   AND (gg.timemodified > ?
                    OR gg.timecreated > ?)
                   AND (gg.finalgrade IS NOT NULL
                    OR gg.rawgrade IS NOT NULL
                    OR gg.feedback IS NOT NULL)
                   AND gi.itemtype = 'mod'
                 ORDER BY gg.timemodified DESC";

        $params = array_merge($params, [$USER->id, $showfrom, $showfrom]);
        $grades = $DB->get_records_sql($sql, $params, 0, 5);

        $eventdata = array();
        foreach ($grades as $grade) {
            $eventdata[] = $grade;
        }

        return $eventdata;
    }

    /**
     * Get all ungraded items.
     * @param int $userid
     * @return array
     */
    public static function all_ungraded($userid) {
        $courseids = self::gradeable_courseids($userid);

        if (empty($courseids)) {
            return array();
        }

        $mods = \core_plugin_manager::instance()->get_installed_plugins('mod');
        $mods = array_keys($mods);

        $grading = [];
        foreach ($mods as $mod) {
            $class = '\theme_remui\activity';
            $method = $mod.'_ungraded';
            if (method_exists($class, $method)) {
                $grading = array_merge($grading, call_user_func([$class, $method], $courseids));
            }
        }

        usort($grading, array('self', 'sort_graded'));

        return $grading;
    }

    /**
     * Get courses where user has the ability to view the gradebook.
     *
     * @param int $userid
     * @return array
     * @throws \coding_exception
     */
    public static function gradeable_courseids($userid) {
        $courses = enrol_get_all_users_courses($userid);
        $courseids = [];
        $capability = 'gradereport/grader:view';
        foreach ($courses as $course) {
            if (has_capability($capability, \context_course::instance($course->id), $userid)) {
                $courseids[] = $course->id;
            }
        }
        return $courseids;
    }

    /**
     * Returns user object by passing user id.
     *
     * @param int $userid.
     * @return object user
     */
    public static function get_user($userorid = false) {
        global $USER, $DB;

        if ($userorid === false) {
            return $USER;
        }

        if (is_object($userorid)) {
            return $userorid;
        } else if (is_number($userorid)) {
            if (intval($userorid) === $USER->id) {
                $user = $USER;
            } else {
                $user = $DB->get_record('user', ['id' => $userorid]);
            }
        } else {
            throw new coding_exception(get_string('parametermustbeobjectorintegerorstring', 'theme_remui', $userorid));
        }

        return $user;
    }

    /**
     * Some moodle functions don't work correctly with specific userids and this provides a hacky workaround.
     *
     * Temporarily swaps global USER variable.
     * @param bool|stdClass|int $userorid
     */
    public static function swap_global_user($userorid = false) {
        global $USER;
        static $origuser = [];
        $user = self::get_user($userorid);
        if ($userorid !== false) {
            $origuser[] = $USER;
            $USER = $user;
        } else {
            $USER = array_pop($origuser);
        }
    }

    /**
     * Get recent courses accessed by user
     *
     * @param Integer $limit
     * @return Array List of courses
     */
    public static function get_recent_accessed_courses($limit) {
        global $USER, $DB;
        $sql = 'SELECT ul.courseid, c.fullname
            FROM {user_lastaccess} ul
            JOIN {course} c ON c.id = ul.courseid
            WHERE userid = ?
            ORDER BY timeaccess
            DESC';
        $params = array ('userid' => $USER->id);
        $courses = $DB->get_records_sql($sql, $params, 0, $limit);
        if ($courses) {
            return $courses;
        }
        return array();
    }

    /**
     * Get Course Stats.
     *
     * @param Integer $course
     * @return Array Course stats.
     */
    public static function get_course_stats($course) {
        $stats = array();
        $coursecontext = \context_course::instance($course->id);
        // 'moodle/course:isincompletionreports' - this capability is allowed to only students
        $enrolledusers = get_enrolled_users($coursecontext, 'moodle/course:isincompletionreports');
        $stats['enrolledusers'] = count($enrolledusers);
        $stats['completed'] = 0;
        $stats['inprogress'] = 0;
        $stats['notstarted'] = 0;

        $completion = new \completion_info($course);
        if ($completion->is_enabled()) {
            $inprogress = 0;
            $completed = 0;
            $notstarted = 0;
            $modules = $completion->get_activities();
            foreach ($enrolledusers as $user) {
                $activitiesprogress = 0;
                foreach ($modules as $module) {
                    $moduledata = $completion->get_data($module, false, $user->id);
                    $activitiesprogress += $moduledata->completionstate == COMPLETION_INCOMPLETE ? 0 : 1;
                }
                if ($activitiesprogress == 0) {
                    $notstarted++;
                } else if ($activitiesprogress == count($modules)) {
                    $completed++;
                } else {
                    $inprogress++;
                }
            }
            $stats['completed'] = $completed;
            $stats['inprogress'] = $inprogress;
            $stats['notstarted'] = $notstarted;
        }
        return $stats;
    }

    /**
     * This function is used to get the data for footer section.
     *
     * @return array of footer sections data
     */
    public static function get_footer_data($social = false) {
        $footer = array();
        $colcount = 0;
        for ($i = 0; $i < 4; $i++) {
            if ($i == 0) {
                $footer['social'] = array(
                    'facebook' => \theme_remui\toolbox::get_setting('facebooksetting'),
                    'twitter'  => \theme_remui\toolbox::get_setting('twittersetting'),
                    'linkedin' => \theme_remui\toolbox::get_setting('linkedinsetting'),
                    'gplus'    => \theme_remui\toolbox::get_setting('gplussetting'),
                    'youtube'  => \theme_remui\toolbox::get_setting('youtubesetting'),
                    'instagram' => \theme_remui\toolbox::get_setting('instagramsetting'),
                    'pinterest' => \theme_remui\toolbox::get_setting('pinterestsetting')
                );
                $footer['social'] = array_filter($footer['social']); // remove empty elements
                if (!empty($footer['social'])) {
                    $colcount++;
                }
            } else {
                // Skip footer content if only social.
                if ($social) {
                    continue;
                }

                $title = format_text(\theme_remui\toolbox::get_setting('footercolumn'.$i.'title'), FORMAT_HTML);
                $content = format_text(\theme_remui\toolbox::get_setting('footercolumn'.$i.'customhtml'));
                if (!empty($title) || !empty($content)) {
                    $footer['sections'][] = array(
                        'title' => $title,
                        'content' => $content
                    );
                    $colcount++;
                }
            }
        }

        // Skip footer content if only social.
        if (!$social) {
            $footer['bottomtext'] = \theme_remui\toolbox::get_setting('footerbottomtext');
            $footer['bottomlink'] = \theme_remui\toolbox::get_setting('footerbottomlink');
            $footer['poweredby']  = \theme_remui\toolbox::get_setting('poweredbyedwiser');
            // To handle number of columns in footer row.
            $classes = 'col-12 ';
            if ($colcount == 4) {
                $classes .= "col-sm-6 col-lg-3";
            } else if ($colcount == 3) {
                $classes .= "col-sm-6 col-lg-4";
            } else if ($colcount == 2) {
                $classes .= "col-sm-6";
            }
            $footer['classes'] = $classes;
        }
        return $footer;
    }

    /**
     * Returns the data for course filter.
     */
    public static function get_course_filters_data() {
        global $PAGE;
        $filterdata = array();
        $catdata = array();
        $categories = \core_course_category::make_categories_list();
        foreach ($categories as $key => $value) {
            $category = new \stdClass();
            $category->id = $key;
            $category->title = $value;
            array_push($catdata, $category);
        }
        $filterdata['catdata'] = $catdata;
        $filterdata['searchhtml'] = '<div class="my-10  col-lg-4 col-md-9
        col-sm-12">'.$PAGE->get_renderer('core', 'course')->course_search_form('', '', '', 0).
        '</div>';
        return $filterdata;
    }

    /*
     * Get course image.
     */
    public static function get_course_image($corecourselistelement, $islist = false) {
        global $CFG, $OUTPUT;
        if (!$islist) {
            $corecourselistelement = new core_course_list_element($corecourselistelement);
        }

        // Course image.
        foreach ($corecourselistelement->get_course_overviewfiles() as $file) {
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
        if (!empty($courseimage)) {
            return $courseimage;
        } else {
            return $OUTPUT->get_generated_image_for_id($corecourselistelement->id);
        }
    }

    /**
     * Function to get the remote data from url
     *
     * @param string $url
     * @return array
     */
    public static function url_get_contents ($url) {
        $urlgetcontentsdata = array();
        if (function_exists('curl_exec')) {
            $conn = curl_init($url);
            curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($conn, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($conn, CURLOPT_TIMEOUT, 3);
            if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
                curl_setopt($conn, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            }
            $urlgetcontentsdata = (curl_exec($conn));
            if (curl_errno($conn)) {
                $errormsg = curl_error($conn);
                $urlgetcontentsdata = array();
            }
            curl_close($conn);
        } else if (function_exists('file_get_contents')) {
            $urlgetcontentsdata = file_get_contents($url);
        } else if (function_exists('fopen') && function_exists('stream_get_contents')) {
            $handle = fopen($url, "r");
            $urlgetcontentsdata = stream_get_contents($handle);
        } else {
            $urlgetcontentsdata = array();
        }
        return $urlgetcontentsdata;
    }

    /**
     * Returns the latest announcements related to RemUI.
     *
     * @return array
     */
    public static function get_remui_announcemnets() {
        global $DB;
        $transexpired = false;
        $announcementsdata = array();
        // Get existing data.
        $transvar = $DB->get_field_select('config_plugins', 'value', 'name = :name',
        array('name' => 'remui_announcements_trans'), IGNORE_MISSING);
        if ($transvar) {
            $transvar = unserialize($transvar);
            if (is_array($transvar) && time() > $transvar[1] && $transvar[1] > 0) {
                $transexpired = true;
                try {
                    $DB->delete_records_select('config_plugins', 'name = :name', array('name' => 'remui_announcements_trans'));
                } catch (dml_exception $e) {
                    // keep catch empty if no record found.
                }
            } else {
                // Return announcements data if not expired.
                return $transvar[0];
            }
        } else {
            $transexpired = true;
        }
        if ($transexpired) {
            // Delete previous data and insert new data.
            try {
                $DB->delete_records_select('config_plugins', 'name = :name', array('name' => 'remui_announcements_trans'));
            } catch (dml_exception $e) {
                // Keep catch empty if no record found.
            }

            $announcementsdata = self::url_get_contents('http://remui.edwiser.org/remui_announcements.json');
            // Decode the JSON into an associative array.
            $announcementsdata = json_decode($announcementsdata, true);
            $time = time() + 60 * 60 * 24 * 4;
            // Insert new announcements data.
            $dataobject = new \stdClass();
            $dataobject->plugin = 'theme_remui';
            $dataobject->name = 'remui_announcements_trans';
            $dataobject->value = serialize(array($announcementsdata, $time));
            $DB->insert_record('config_plugins', $dataobject);
        }
        return $announcementsdata;
    }

    /**
     * Check if update available by using json data
     *
     * @return String status.
     */
    public static function check_remui_update() {
        global $CFG;

        $response   = self::get_remui_announcemnets();
        $updateinfo = @$response['updateinfo'];
        $currentmoodle = substr($CFG->release, 0, 3);

        if (!empty($updateinfo) && isset($updateinfo[$currentmoodle])) {
            // Get remui installed status.
            $pm = \core_plugin_manager::instance();
            $currentremui = $pm->get_plugin_info('theme_remui')->release;

            if ($updateinfo[$currentmoodle]['version'] > $currentremui) {
                return 'available';
            }
        }
        return '';
    }

    /**
     * This function will return the messaging panel html only.
     * In latest Moodle they have removed the core_message_render_navbar_output function
     * and Now returns two panels in same function
     * Following code is from same function message_popup_render_navbar_output but excluding the notification
     */
    public static function render_navbar_output() {
        global $USER, $OUTPUT;
        $unreadcount = \core_message\api::count_unread_conversations($USER);
        $requestcount = \core_message\api::get_received_contact_requests_count($USER->id);
        $msgcontext = [
            'userid' => $USER->id,
            'unreadcount' => $unreadcount + $requestcount
        ];
        return $OUTPUT->render_from_template('core_message/message_popover', $msgcontext);
    }
}
