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

user_preference_allow_ajax_update('pinaside', PARAM_ALPHA);
global $USER, $PAGE;

$PAGE->requires->strings_for_js(['sidebarpinned', 'sidebarunpinned'], 'theme_remui');

$blockshtml = $OUTPUT->blocks('side-pre', array(), 'aside');
$hasblocks  = strpos($blockshtml, 'data-block=') !== false;
$usercanmanage = \theme_remui\utility::check_user_admin_cap();

// check aside right state
if (isloggedin()) {
    $pinaside = get_user_preferences('pinaside', '');

    $activities = array("book", "quiz");
    if (isset($PAGE->cm->id) && in_array($PAGE->cm->modname, $activities)) {
        $pinaside = 'pinaside';
    }
} else {
    $pinaside = '';
}


// if no blocks in sidebar, it will always be overlay (no pin option)
if (!$hasblocks) {
    $pinaside = '';
}

$extraclasses = [];
$extraclasses [] = $pinaside;

// classes to show right sidebar only if one of the below is true
if ($hasblocks) {
    $extraclasses [] = 'sidebar-pinned sidebar-open';
}

if ($hasblocks || $usercanmanage) {
    $initrightsidebar = true;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'footerdata' => \theme_remui\utility::get_footer_data(),
    'sesskey'       => $USER->sesskey,
    'navbarinverse' => \theme_remui\toolbox::get_setting('navbarinverse'),
    'sidebarcolor' => \theme_remui\toolbox::get_setting('sidebarcolor'),
    \theme_remui\toolbox::get_setting('sitecolor', 'primary') => 'true',
    'initrightsidebar' => $initrightsidebar,
];

// for all partials
$templatecontext['sitecolor']  = \theme_remui\toolbox::get_setting('sitecolor');

echo $OUTPUT->render_from_template('theme_remui/secure', $templatecontext);

