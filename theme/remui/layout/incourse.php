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
require_once('common.php');

// Prepare activity sidebar context.
global $COURSE, $PAGE;
$isactivitypage = false;
if (isset($PAGE->cm->id) && $COURSE->id != 1 && $COURSE->format != 'singleactivity') {
    $isactivitypage = true;
}
$templatecontext['isactivitypage'] = $isactivitypage;
$templatecontext['courseurl'] = course_get_url($COURSE->id);
$templatecontext['activitysections'] = \theme_remui\utility::get_activity_list();

$flatnavigation = $PAGE->flatnav;
foreach ($flatnavigation as $navs) {
    if ($navs->key === 'addblock') {
        $templatecontext['addblock'] = $navs;
        break;
    }
}

echo $OUTPUT->render_from_template('theme_remui/incourse', $templatecontext);