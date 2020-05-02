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
 * Renderables class
 * @package block_remuiblck
 * @author  2019 WisdmLabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_remuiblck\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;
use context_system;
use \block_remuiblck\coursehandler as coursehandler;
use \block_remuiblck\userhandler as userhandler;
use stdClass;

require_once($CFG->dirroot . '/blocks/remuiblck/lib.php');

class remuiblck_courseprogress implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Return true if current user can view this block
     * @return bool True if user can view
     */
    public function can_view() {
        $roles = array_diff(array_values($this->options['roles']), array('student'));
        if (!isset($roles['admin']) && empty($roles)) {
            return false;
        }
        return true;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;
        $context = new stdClass();
        $context->block = $this->block;
        return $context;
    }
}


class remuiblck_userstats implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;
        // require_once($CFG->dirroot . '/blocks/remuiblck/classes/userstats.php');

        $context = new stdClass();
        $context->block = $this->block;

        $userobj = userhandler::get_instance();
        $userdata  = $userobj->enrolled_users_state();

        $quizstats = $userobj->get_quiz_stats();
        $context->is_siteadmin = true;
        $context->data     = $userdata;
        $context->quizdata = $quizstats;

        return $context;
    }
}


class remuiblck_enrolledusers implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;

        $context = new stdClass();
        $context->block = $this->block;

        $userobj = userhandler::get_instance();
        $userdata = $userobj->enrolled_users_state();

        $context->is_siteadmin = is_siteadmin();
        $context->data = $userdata;

        return $context;
    }
}


class remuiblck_quizattempts implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;

        $context = new stdClass();
        $context->block = $this->block;

        $userobj = userhandler::get_instance();
        $quizstats = $userobj->get_quiz_stats();
        $context->is_siteadmin = is_siteadmin();
        $context->quizdata = $quizstats;
        return $context;
    }
}



class remuiblck_courseanlytics implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Return true if current user can view this block
     * @return bool True if user can view
     */
    public function can_view() {
        if (!isset($roles['admin']) && empty($this->options['roles'])) {
            return false;
        }
        return true;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;

        $context = new stdClass();
        $context->block = $this->block;

        $obj = coursehandler::get_instance();
        $data = $obj->get_analytics_overview();
        $context->quizcourse = $data['quizcourse'];
        $context->hasanalytics = $data['hasanalytics'];
        $perpage = get_user_preferences('courseanalyticsperpage', 5);
        $barperpagename = 'entries'.$perpage;
        $context->perpage = $perpage;
        $context->$barperpagename = true;
        return $context;
    }
}

class remuiblck_latestmembers implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Return true if current user can view this block
     * @return bool True if user can view
     */
    public function can_view() {
        $roles = array_diff(array_values($this->options['roles']), array('student'));
        if (!isset($roles['admin']) && empty($roles)) {
            return false;
        }
        return true;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;
        $context = new stdClass();
        $context->block = $this->block;
        return $context;
    }
}

class remuiblck_addnotes implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Return true if current user can view this block
     * @return bool True if user can view
     */
    public function can_view() {
        return !empty($this->options['roles']);
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;
        // require_once($CFG->dirroot . '/blocks/remuiblck/classes/coursestats.php');

        $context = new stdClass();
        $context->block = $this->block;

        $obj = coursehandler::get_instance();
        $courses = $obj->get_notes_data();
        if ($courses) {
            $context->has_courses = true;
            $context->courses = array_values($courses);
        }
        return $context;
    }
}

class remuiblck_recentfeedback implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Return true if current user can view this block
     * @return bool True if user can view
     */
    public function can_view() {
        return !empty($this->options['roles']);
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;
        $context = new stdClass();
        $context->block = $this->block;
        return $context;
    }
}



class remuiblck_recentforums implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Return true if current user can view this block
     * @return bool True if user can view
     */
    public function can_view() {
        return !empty($this->options['roles']);
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;
        $context = new stdClass();
        $context->block = $this->block;
        return $context;
    }
}




class remuiblck_mainsection implements renderable, templatable {
    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE;
        $output = $output;
        $editing = $PAGE->user_is_editing();

        $context = array();
        // passing parameters first 'true' to get only allowed blocks and
        // second 'true' to fetch user saved preferences
        $blockslist = get_blocks_list(true, true);

        $toplist    = array();
        $leftlist   = array();
        $rightlist  = array();

        $userhandler = userhandler::get_instance();
        $roles = $userhandler->get_user_roles_system_wide();

        if ($editing) {
            $contenttop = new stdClass;
            $contenttop->blockname = get_string('topemptybox', 'block_remuiblck');
            $contenttop->id = 'nonemptytop';
            $contenttop->content = get_string('emptyboxmsg', 'block_remuiblck');
            $contenttop->classes = 'hidden';
            $toplist[] = $contenttop;

            $contentleft = new stdClass;
            $contentleft->blockname = get_string('leftemptybox', 'block_remuiblck');
            $contentleft->id = 'nonemptyleft';
            $contentleft->content = get_string('emptyboxmsg', 'block_remuiblck');
            $contentleft->classes = 'hidden';
            $leftlist[] = $contentleft;

            $contentright = new stdClass;
            $contentright->blockname = get_string('rightemptybox', 'block_remuiblck');
            $contentright->id = 'nonemptyright';
            $contentright->content = get_string('emptyboxmsg', 'block_remuiblck');
            $contentright->classes = 'hidden';
            $rightlist[] = $contentright;
        }
        foreach ($blockslist as $key => $block) {
            $content = generate_block($key, array('roles' => $roles));
            if ($content == "") {
                continue;
            }
            $contentobj = new stdClass;
            $contentobj->blockname = get_string($key, 'block_remuiblck');
            $contentobj->id      = $key;
            $contentobj->content = $content;
            $contentobj->classes = '';
            $contentobj->dragable = true;

            switch ($block['side']) {
                case 'top':
                    $toplist[] = $contentobj;
                    break;
                case 'left':
                    $leftlist[] = $contentobj;
                    break;
                case 'right':
                    $rightlist[] = $contentobj;
                    break;
                default:
                    break;
            }
        }
        $obj = new stdClass();
        $obj->name  = 'col-12';
        $obj->id    = 'sortable1';
        $obj->editing = $editing;
        $obj->classes = 'col-lg-12 connectedSortable';

        if ($editing && count($toplist) < 2 ) {
            $obj->classes = $obj->classes .' blankheight';
        }
        $obj->content = $toplist;
        $context['sectiondata'][] = $obj;
        $obj = new stdClass();
        $obj->name = 'col-6';
        $obj->id = 'sortable2';
        $obj->editing = $editing;
        $obj->classes = 'col-lg-6 connectedSortable';
        if ($editing && count($leftlist) < 2 ) {
            $obj->classes = $obj->classes .' blankheight';
        }
        $obj->content = $leftlist;
        $context['sectiondata'][] = $obj;

        $obj = new stdClass();
        $obj->name = 'col-6';
        $obj->id = 'sortable3';
        $obj->editing = $editing;
        $obj->classes = 'col-lg-6 connectedSortable';
        if ($editing && count($rightlist) < 2 ) {
            $obj->classes = $obj->classes .' blankheight';
        }
        $obj->content = $rightlist;
        $context['sectiondata'][] = $obj;
        return $context;
    }
}

class remuiblck_managecourses implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        $output = $output;
        $context = [];
        $userhandler = userhandler::get_instance();
        $context['isteacher'] = $userhandler->is_teacher_in_any_course();
        $view = get_user_preferences('managecourseview', 'card');
        $context[$view] = true;
        $perpage = get_user_preferences('managecourseperpage', 5);
        $context['perpage'] = $perpage;
        $coursesperpagename = 'entries'.$perpage;
        $context[$coursesperpagename] = true;
        return $context;
    }
}


class remuiblck_coursereport implements renderable, templatable {

    /**
     * @var courseid
     */
    private $courseid = null;

    /**
     * @var courseid
     */

    /**
     * Constructor.
     *
     * @param courseid $courseid id of course
     */
    public function __construct($courseid) {
        $this->courseid = $courseid;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $CFG;
        $output = $output;
        $context = new stdClass();
        require_once($CFG->dirroot . '/blocks/remuiblck/classes/coursehandler.php');
        $handler = coursehandler::get_instance();
        $stats = $handler->get_course_stats(get_course($this->courseid));
        $context->enrolledstats = $stats['enrolledusers'] == 0 ? false : $stats;
        $context->nostatsimg = $output->image_url('chart', 'block_remuiblck')->out();
        $context->courseid = $this->courseid;
        foreach (COURSE_MANAGE_PIE_COLOR as $color => $value) {
            $context->$color = $value;
        }
        return $context;
    }
}


class remuiblck_scheduletask implements renderable, templatable {

    /**
     * $block name of block
     * @var null
     */
    private $block = null;

    /**
     * $options extra options data
     * @var array
     */
    private $options = array();

    /**
     * Constructor.
     *
     * @param block $block name of blocks
     * @param array options extra options data
     */
    public function __construct($block, $options) {
        $this->block   = $block;
        $this->options = $options;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $PAGE;
        $PAGE->requires->data_for_js('contextid', context_system::instance()->id);
        $output = $output;
        $context = new stdClass();
        $context->block = $this->block;
        return $context;
    }
}
