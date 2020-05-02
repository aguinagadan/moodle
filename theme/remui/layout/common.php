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
 * A two column layout for the remui theme.
 *
 * @package   theme_remui
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/behat/lib.php');

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('pin_aside', PARAM_ALPHA);
user_preference_allow_ajax_update('course_view_state', PARAM_ALPHA);

global $PAGE, $CFG;

$PAGE->requires->strings_for_js(['sidebarpinned', 'sidebarunpinned', 'pinsidebar', 'unpinsidebar'], 'theme_remui');

// RemUI Usage Tracking (RemUI Analytics)
$ranalytics = new \theme_remui\usage_tracking();
$ranalytics->send_usage_analytics();

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
    $rightsidebar = (get_user_preferences('pin_aside', 'true') == 'true');
    // Always pinned for quiz and book activity.
    $activities = array("book", "quiz");
    if (isset($PAGE->cm->id) && in_array($PAGE->cm->modname, $activities) || $PAGE->user_is_editing()) {
        $rightsidebar = true;
    }
} else {
    $navdraweropen = false;
    $rightsidebar = false;
}
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
// Message drawer html and drawer toggle in sidebar tabs.
$mergemessagingsidebar = \theme_remui\toolbox::get_setting('mergemessagingsidebar');
$messagedrawer = '';
$messagetoggle = '';
if ($mergemessagingsidebar) {
    $messagedrawer = core_message_standard_after_main_region_html();
    // $messagetoggle = core_message_render_navbar_output($OUTPUT);
    $messagetoggle = \theme_remui\utility::render_navbar_output();
}
$unreadrequestcount = 0;
if ($messagetoggle) {
    $unreadcount = \core_message\api::count_unread_conversations($USER);
    $requestcount = \core_message\api::get_received_contact_requests_count($USER->id);
    $unreadrequestcount = $unreadcount + $requestcount;
}
$usercanmanage = \theme_remui\utility::check_user_admin_cap();
$initrightsidebar = false;
$hasmessaging  = empty($messagedrawer) !== true;

$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
if ($PAGE->pagelayout == 'mypublic') {
    $extraclasses [] = ' page-profile ';
}
if ($hasblocks) {
    $extraclasses[] = 'hasblocks';
    if ($rightsidebar) {
        $extraclasses[] = 'sidebar-pinned';
    }
} else {
    $messagetoggle = str_replace(
        'class="nav-link popover-region-toggle p-3"',
        'class="nav-link popover-region-toggle active show p-3"',
        $messagetoggle
    );
}

if ($hasblocks || $usercanmanage || $hasmessaging) {
    $initrightsidebar = true;
}

if ($mergemessagingsidebar) {
    $extraclasses[] = 'mergemessagingsidebar';
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$sitecolor = get_config('theme_remui', 'sitecolor');
$sitecolor = ($sitecolor == "") ? 'primary' : $sitecolor;
$navbarinverse = get_config('theme_remui', 'navbarinverse');
$sidebarcolor = get_config('theme_remui', 'sidebarcolor');
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'cansignup' => (!empty($CFG->registerauth) && (!isloggedin() || isguestuser())),
    'signupurl' => new moodle_url('/login/signup.php'),
    'footerdata' => \theme_remui\utility::get_footer_data(),
    'usercanmanage' => $usercanmanage,
    'messagedrawer' => $messagedrawer,
    'initrightsidebar' => $initrightsidebar,
    'messagetoggle' => $messagetoggle,
    'navbarinverse' => $navbarinverse,
    'sidebarcolor' => $sidebarcolor,
    'unreadrequestcount' => $unreadrequestcount,
    'pinaside' => $rightsidebar,
    $sitecolor => true,
];

$flatnavigation = $PAGE->flatnav;
$mycourseschild = [];
foreach ($flatnavigation as $navs) {
    $navs->show = true;
    if ($navs->key == "mycourses") {
        $templatecontext['mycoursestab'] = true;
        $templatecontext['mycoursesurl'] = $CFG->wwwroot . "/course/index.php?mycourses=1";
    }
    if (isset($navs->parent->key) && $navs->parent->key == 'mycourses') {
        if ($navs->key = "courseindexpage" && $navs->type == 60) {
            $navs->action = $CFG->wwwroot . "/course/index.php?mycourses=1";;
        }
        $mycourseschild[] = $navs;
        $navs->show = false;
    }
}
$templatecontext['mycourseschild'] = $mycourseschild;
$templatecontext['flatnavigation'] = $flatnavigation;
$templatecontext['firstcollectionlabel'] = $flatnavigation->get_collectionlabel();
$templatecontext['navfootermenu'] = \theme_remui\utility::get_left_nav_footer_menus();

if (isloggedin() && \theme_remui\toolbox::get_setting('enablerecentcourses')) {
    $courses = \theme_remui\utility::get_recent_accessed_courses(5);
    $finalarr = array();
    foreach ($courses as $key => $course) {
        $templatecontext['hasrecent'] = true;
        $finalarr[] = array (
            'id' => $course->courseid,
            'fullname' => format_text($course->fullname)
        );
    }
    $templatecontext['recentcourses'] = $finalarr;
}

$templatecontext['enabledictionary'] = \theme_remui\toolbox::get_setting('enabledictionary');
if (strpos($bodyattributes, 'editing') !== false) {
    $templatecontext['editingenabled'] = true;
}
