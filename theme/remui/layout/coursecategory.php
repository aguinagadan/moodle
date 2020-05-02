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
 * @copyright  (c) 2018 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE, $USER, $SITE, $COURSE;

require_once('common.php');


// Generate page url.
$pageurl = new moodle_url('/course/index.php');
$mycourses  = optional_param('mycourses', 0, PARAM_INT);

// Get the filters first.
$filterdata = \theme_remui\utility::get_course_filters_data();
$templatecontext['categories'] = $filterdata['catdata'];
$templatecontext['searchhtml'] = $filterdata['searchhtml'];

$templatecontext['tabcontent'] = array();

if (isloggedin()) {
    // Tab creation Content.
    $mycoursesobj = new stdClass();
    $mycoursesobj->name = 'mycourses';
    $mycoursesobj->text = get_string('mycourses', 'theme_remui');
    if ($mycourses) {
        $mycoursesobj->isActive = true;
    }
    $templatecontext['tabcontent'][] = $mycoursesobj;
}

$coursesobj = new stdClass();
$coursesobj->name = 'courses';
$coursesobj->text = get_string('courses', 'theme_remui');
if (!$mycourses) {
    $coursesobj->isActive = true;
}
$templatecontext['tabcontent'][] = $coursesobj;

$templatecontext['mycourses'] = $mycourses;
if (\theme_remui\toolbox::get_setting('enablenewcoursecards')) {
    $templatecontext['latest_card'] = true;
}

echo $OUTPUT->render_from_template('theme_remui/coursearchive', $templatecontext);
