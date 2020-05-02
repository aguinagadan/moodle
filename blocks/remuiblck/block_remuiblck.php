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
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/blocks/remuiblck/lib.php');
class block_remuiblck extends block_base
{
    public function init() {
        $this->title = get_string('remuiblck', 'block_remuiblck');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {

        user_preference_allow_ajax_update('managecourseview', PARAM_ALPHA);
        user_preference_allow_ajax_update('managecourseperpage', PARAM_INT);
        user_preference_allow_ajax_update('courseanalyticsperpage', PARAM_INT);
        user_preference_allow_ajax_update('remui_layout_top', PARAM_RAW);
        user_preference_allow_ajax_update('remui_layout_left', PARAM_RAW);
        user_preference_allow_ajax_update('remui_layout_right', PARAM_RAW);

        global $PAGE, $CFG;
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = generate_block('mainsection');

        $PAGE->requires->strings_for_js([
            'enrolledusers',
            'studentcompleted',
            'inprogress',
            'yettostart',
            'nosavebutton',
            'nostudentsenrolled',
            'searchnameemail',
            'exportcsv',
            'deletetask',
            'deletetaskmessage',
            'taskdeleted',
            'noofstudents',
            'showingfromto',
            'selectastudent',
            'nousersenrolledincourse'
        ], 'block_remuiblck');
        $PAGE->requires->strings_for_js([
            'sendmessage',
            'send',
            'sendmessageto'
        ], 'core_message');
        $PAGE->requires->strings_for_js(['enrolusers'], 'core_enrol');
        $PAGE->requires->strings_for_js(['save', 'saving'], 'core_repository');
        $PAGE->requires->strings_for_js(['graderreport'], 'core_grades');
        $PAGE->requires->strings_for_js(['editcourse'], 'theme_remui');
        $PAGE->requires->strings_for_js(['nomatchingcourses'], 'core_backup');
        $PAGE->requires->strings_for_js([
            'activityreport',
            'coursereport',
            'ok',
            'search',
            'next',
            'previous',
            'first',
            'last',
            'show',
            'entries',
            'total'
        ], 'moodle');
        $PAGE->requires->data_for_js('systemcontextid', \context_system::instance()->id);
        foreach (COURSE_MANAGE_PIE_COLOR as $color => $value) {
            $PAGE->requires->data_for_js($color, $value);
        }
        if ($CFG->branch <= 36) {
            $PAGE->requires->css(new moodle_url('/blocks/remuiblck/styles/moodle_35_fixes.css'));
            $PAGE->requires->js_call_amd('block_remuiblck/moodle_35_page_globals', 'init');
            $PAGE->requires->data_for_js('formoodle35', true);
        }
        return $this->content;
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function applicable_formats() {
        return array('my' => true);
    }
}
