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

require_once($CFG->dirroot."/blocks/remuiblck/classes/output/renderable.php");

define('COURSE_MANAGE_PIE_COLOR', array(
    'enrolleduserscolor' => "#E4EAEC",
    'studentcompletedcolor' => "#008C4D",
    'inprogresscolor' => "#0B69E3",
    'yettostartcolor' => "#F57D1B"
));
/*
 * Block generation
 * $block => Block Name
 */
function generate_block($block, $options = array()) {
    global $PAGE;
    $theme = $PAGE->theme->name; // Current theme
    $pthemes = $PAGE->theme->parents; // Parent themes

    /* If theme is not remui or not a child theme of remui
    * then remui block will not generate remui blocks
    */
    if (!($theme == 'remui' || in_array("remui", $pthemes))) {
        return '';
    }

    $classname = '\block_remuiblck\output\remuiblck_'. $block;

    if (!class_exists($classname)) {
        return "";
    }

    $renderable = new $classname($block, $options);

    if (method_exists($renderable, 'can_view') && !$renderable->can_view()) {
        return '';
    }
    return get_content_from_renderer('block_remuiblck', $renderable);
}

function get_content_from_renderer($block, $renderable) {
    global $PAGE;
    $renderer = $PAGE->get_renderer($block);
    $content = $renderer->render($renderable);
    return $content;
}

/*
 * This function will return the blocks list
 * with the enabled value and the block side i.e. left, right or top parameters
 * boolean allowedonly  => check the settings for the blocks added by admin
 * boolean userpref => get the user preference for blocks positioning
 */
function get_blocks_list($allowedonly = false, $userpref = false) {

    // retrieve the blocks list from

    $blockslist = get_default_blocks_list();

    // list the blocks allowed
    if ($allowedonly) {
        $blockslist = get_list_of_blocks_allowed($blockslist);
    }

    // check saved state of blocks
    if ($userpref) {
        $blockslist = sort_according_saved_pos($blockslist);
    }
    return $blockslist;
}


/*
 * This function will return list of blocks
 * which are marked allowed in settings
 */
function get_list_of_blocks_allowed($blockslist) {

    foreach ($blockslist as $key => $value) {
        $value = $value;
        $allow = get_config('theme_remui', 'enable'.$key.'block');
        if ($allow) {
            $blockslist[$key]['enable'] = 1;
        } else {
            unset($blockslist[$key]);
        }
    }
    return $blockslist;
}

/*
 * This function will return list of blocks
 * according to saved position by user
 */
function sort_according_saved_pos($blockslist) {
    $layouttop   = json_decode(get_user_preferences('remui_layout_top'));
    $layoutleft  = json_decode(get_user_preferences('remui_layout_left'));
    $layoutright = json_decode(get_user_preferences('remui_layout_right'));
    $finalarr = array();
    if ($layouttop || $layoutleft || $layoutright) {

        foreach ($layouttop as $key => $value) {
            if (array_key_exists($value, $blockslist)) {
                $blockslist[$value]['side'] = 'top';
                $finalarr[$value] = $blockslist[$value];
                unset($blockslist[$value]);
            }
        }

        foreach ($layoutleft as $key => $value) {
            if (array_key_exists($value, $blockslist)) {
                $blockslist[$value]['side'] = 'left';
                $finalarr[$value] = $blockslist[$value];
                unset($blockslist[$value]);
            }
        }

        foreach ($layoutright as $key => $value) {
            if (array_key_exists($value, $blockslist)) {
                $blockslist[$value]['side'] = 'right';
                $finalarr[$value] = $blockslist[$value];
                unset($blockslist[$value]);
            }
        }
    }

    foreach ($blockslist as $key => $value) {
         $finalarr[$key] = $value;
    }
    return $finalarr;

}

/*
 * This function will return default blocks position
 */
function get_default_blocks_list() {
    $blockslist = [
        'courseanlytics' => array('enable' => 0, 'side' => 'top'),
        'courseprogress' => array('enable' => 0, 'side' => 'top'),
        'enrolledusers'  => array('enable' => 0, 'side' => 'top'),
        'managecourses'  => array('enable' => 0, 'side' => 'top'),
        'scheduletask'  => array('enable' => 0, 'side' => 'left'),
        'addnotes'       => array('enable' => 0, 'side' => 'left'),
        'quizattempts'   => array('enable' => 0, 'side' => 'left'),
        'latestmembers'  => array('enable' => 0, 'side' => 'right'),
        'recentfeedback' => array('enable' => 0, 'side' => 'right'),
        'recentforums'   => array('enable' => 0, 'side' => 'right'),
    ];
    return $blockslist;
}

function get_date_difference($timecreated, $currenttime) {
    $date1 = new DateTime();
    $date1->setTimeStamp($currenttime);
    $date2 = new DateTime();
    $date2->setTimeStamp($timecreated);
    return date_diff($date1, $date2);
}

/*
 * Callback function for modal fragment in ToDoList Block
 */
function block_remuiblck_output_fragment_task_form($args) {
    $taskid = $args['taskid'];
    $mform = new block_remuiblck_task_popup_form($taskid);
    if ($taskid != -1) {
        $taskhandler = new block_remuiblck_taskhandler($taskid);
        $mform->set_data(array(
            'subject' => $taskhandler->get_task()->subject,
            'summary' => $taskhandler->get_task()->summary,
            'due' => $taskhandler->get_task()->timedue,
            'userlist' => json_decode($taskhandler->get_task()->assignedto, true),
            'visible' => $taskhandler->get_task()->visible,
            'notify' => $taskhandler->get_task()->notify,
        ));
    }
    return $mform->render();
}
