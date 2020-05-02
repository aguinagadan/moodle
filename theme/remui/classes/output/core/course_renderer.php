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
 * Edwiser RemUI Course Renderer Class
 * @package    theme_remui
 * @copyright  (c) 2018 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui\output\core;
defined('MOODLE_INTERNAL') || die();

use moodle_url;
use coursecat_helper;
use lang_string;
use core_course_category;
use context_system;
use html_writer;
use core_text;

require_once($CFG->dirroot . '/course/renderer.php');

class course_renderer extends \core_course_renderer {

    /**
     * Returns HTML to print list of available courses for the frontpage
     *
     * @return string
     */
    public function frontpage_available_courses() {
        global $CFG;

        $chelper = new coursecat_helper();
        $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)->set_courses_display_options(array(
                    'recursive' => true,
                    'limit' => $CFG->frontpagecourselimit,
                    'viewmoreurl' => new moodle_url('/course/index.php'),
                    'viewmoretext' => new lang_string('fulllistofcourses')));

        $chelper->set_attributes(array('class' => 'frontpage-course-list-all'));
        $courses = core_course_category::get(0)->get_courses($chelper->get_courses_display_options());
        $totalcount = core_course_category::get(0)->get_courses_count($chelper->get_courses_display_options());
        if (!$totalcount && !$this->page->user_is_editing() && has_capability('moodle/course:create', \context_system::instance())) {
            // Print link to create a new course, for the 1st available category.
            return $this->add_new_course_button();
        }
        $latestcard = get_config('theme_remui', 'enablenewcoursecards');
        $coursehtml = '<div class=""><div class="card-deck slick-course-slider slick-slider ' . ($latestcard ? 'latest-cards' : '') . '">';
        // $courses = array_slice($courses, count($courses) - 10);
        if (!empty($courses)) {
            foreach ($courses as $course) {
                $coursesummary = strip_tags($chelper->get_course_formatted_summary($course, array('overflowdiv' => false, 'noclean' => false, 'para' => false)));
                $coursesummary = strlen($coursesummary) > 100 ? substr($coursesummary, 0, 100)."..." : $coursesummary;
                $image = \theme_remui\utility::get_course_image($course, 1);
                $coursename = strip_tags($chelper->get_course_formatted_name($course));
                if (!$latestcard) {
                    $coursehtml .= "
                    <div class='card w-100 rounded-bottom mx-0 bg-transparent d-inline-flex flex-column' style='height: 100%;'>
                        <div class='m-2 bg-white border' style='height: 100%;'>
                            <div class='rounded-top' style='height: 200px;
                            background-image: url({$image});background-size: cover;background-position: center; box-shadow: 0 2px 5px #cccccc;'>
                            </div>
                            <div class='card-body p-3'>
                                <h4 class='card-title m-1 ellipsis ellipsis-2'>
                                    <a href='{$CFG->wwwroot}/course/view.php?id={$course->id}' class='font-weight-400 blue-grey-600 font-size-18'>
                                        {$coursename}
                                    </a>
                                </h4>
                                <p class='card-text m-1'>{$coursesummary}</p>
                            </div>
                        </div>
                    </div>";
                } else {
                    if (isset($course->startdate)) {
                        $startdate = date('d M, Y', $course->startdate);
                        $day = substr($startdate, 0, 2);
                        $month = substr($startdate, 3, 3);
                        $year = substr($startdate, 8, 4);
                    }
                    $coursehtml .= "
                    <div class='px-1 course_card card '>
                        <div class='wrapper h-100' style='background-image: url({$image});background-size: cover;background-position: center;position: relative;'>
                            <div class='date btn-primary'>
                                <span class='day'>{$day}</span>
                                <span class='month'>{$month}</span>
                                <span class='year'>{$year}</span>
                            </div>
                            <div class='data'>
                                <div class='content' title='Vestibulum purus quam scelerisque ut'>
                                    <span class='author'>Miscellaneous</span>
                                    <h4 class='title'><a href='{$CFG->wwwroot}/course/view.php?id={$course->id}'>{$coursename}</a></h4>
                                    <p class='text'>{$coursesummary}</p>
                                </div>
                            </div>
                        </div>
                    </div>";
                }
            }
        }

        $coursehtml .= '</div></div>';

        $coursehtml .= " <div class='available-courses button-container w-100 text-center '>
            <button type='button' class='btn btn-floating btn-primary btn-prev btn-sm'><i class='icon fa fa-chevron-left' aria-hidden='true'></i></button>
            <button type='button' class='btn btn-floating btn-primary btn-next btn-sm'><i class='icon fa fa-chevron-right' aria-hidden='true'></i></button>
        </div>";

        $coursehtml .= "
        <div class='row'>
            <div class='col-12 text-right'>
                <a href='{$CFG->wwwroot}/course' class='btn btn-primary'>" . get_string('viewallcourses', 'core')."</a>
            </div>
        </div>";

        return $coursehtml;
    }

}
