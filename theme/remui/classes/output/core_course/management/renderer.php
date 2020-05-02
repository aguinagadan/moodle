<?php
// This file is part of The Bootstrap Moodle theme
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
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_boost
 * @copyright   2018 Bas Brands
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui\output\core_course\management;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/course/classes/management_renderer.php");
use html_writer;
use core_course_category;
use moodle_url;
use core_course_list_element;
use lang_string;
use context_system;
use stdClass;
use action_menu;
use action_menu_link;
use action_menu_link_secondary;

/**
 * Main renderer for the course management pages.
 *
 * @package theme_boost
 * @copyright 2013 Sam Hemelryk
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \core_course_management_renderer {
    /**
     * Renderers a key value pair of information for display.
     *
     * @param string $key
     * @param string $value
     * @param string $class
     * @return string
     */
    protected function detail_pair($key, $value, $class ='') {
        $lcol = 'col-md-3';
        $rcol = 'col-md-9';
        if (strpos($key, 'moveselectedcategoriesto') !== false || strpos($key, 'moveselectedcoursesto') !== false) {
            $lcol = 'col-md-12';
            $rcol = 'col-md-12';
        }
        $html = html_writer::start_div('detail-pair row my-2 yui3-g '.preg_replace('#[^a-zA-Z0-9_\-]#', '-', $class));
        $html .= html_writer::div(html_writer::span($key), "pair-key $lcol yui3-u-1-4 font-weight-bold mb-2");
        $html .= html_writer::div(html_writer::span($value), "pair-value $rcol yui3-u-3-4");
        $html .= html_writer::end_div();
        return $html;
    }

    /**
     * Renders bulk actions for categories.
     *
     * @param core_course_category $category The currently selected category if there is one.
     * @return string
     */
    public function category_bulk_actions(core_course_category $category = null) {
        // Resort courses.
        // Change parent.
        if (!core_course_category::can_resort_any() && !core_course_category::can_change_parent_any()) {
            return '';
        }
        $strgo = new lang_string('go');

        $html  = html_writer::start_div('category-bulk-actions bulk-actions');
        $html .= html_writer::div(get_string('categorybulkaction'), 'accesshide', array('tabindex' => '0'));
        if (core_course_category::can_resort_any()) {
            $selectoptions = array(
                'selectedcategories' => get_string('selectedcategories'),
                'allcategories' => get_string('allcategories')
            );
            $form = html_writer::start_div();
            if ($category) {
                $selectoptions = array('thiscategory' => get_string('thiscategory')) + $selectoptions;
                $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'currentcategoryid', 'value' => $category->id));
            }
            $form .= html_writer::div(
                html_writer::select(
                    $selectoptions,
                    'selectsortby',
                    'selectedcategories',
                    false,
                    array('aria-label' => get_string('selectcategorysort'))
                )
            );
            $form .= html_writer::div(
                html_writer::select(
                    array(
                        'name' => get_string('sortbyx', 'moodle', get_string('categoryname')),
                        'namedesc' => get_string('sortbyxreverse', 'moodle', get_string('categoryname')),
                        'idnumber' => get_string('sortbyx', 'moodle', get_string('idnumbercoursecategory')),
                        'idnumberdesc' => get_string('sortbyxreverse' , 'moodle' , get_string('idnumbercoursecategory')),
                        'none' => get_string('dontsortcategories')
                    ),
                    'resortcategoriesby',
                    'name',
                    false,
                    array('aria-label' => get_string('selectcategorysortby'), 'class' => 'mt-1')
                )
            );
            $form .= html_writer::div(
                html_writer::select(
                    array(
                        'fullname' => get_string('sortbyx', 'moodle', get_string('fullnamecourse')),
                        'fullnamedesc' => get_string('sortbyxreverse', 'moodle', get_string('fullnamecourse')),
                        'shortname' => get_string('sortbyx', 'moodle', get_string('shortnamecourse')),
                        'shortnamedesc' => get_string('sortbyxreverse', 'moodle', get_string('shortnamecourse')),
                        'idnumber' => get_string('sortbyx', 'moodle', get_string('idnumbercourse')),
                        'idnumberdesc' => get_string('sortbyxreverse', 'moodle', get_string('idnumbercourse')),
                        'timecreated' => get_string('sortbyx', 'moodle', get_string('timecreatedcourse')),
                        'timecreateddesc' => get_string('sortbyxreverse', 'moodle', get_string('timecreatedcourse')),
                        'none' => get_string('dontsortcourses')
                    ),
                    'resortcoursesby',
                    'fullname',
                    false,
                    array('aria-label' => get_string('selectcoursesortby'), 'class' => 'mt-1')
                )
            );
            $form .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'bulksort',
                'value' => get_string('sort'), 'class' => 'btn btn-secondary my-1'));
            $form .= html_writer::end_div();

            $html .= html_writer::start_div('detail-pair row my-2 yui3-g my-1');
            $html .= html_writer::div(html_writer::span(get_string('sorting')), 'pair-key col-md-12 mb-2 yui3-u-1-4');
            $html .= html_writer::div($form, 'pair-value col-md-12 yui3-u-3-4');
            $html .= html_writer::end_div();
        }
        if (core_course_category::can_change_parent_any()) {
            $options = array();
            if (core_course_category::top()->has_manage_capability()) {
                $options[0] = core_course_category::top()->get_formatted_name();
            }
            $options += core_course_category::make_categories_list('moodle/category:manage');
            $select = html_writer::select(
                $options,
                'movecategoriesto',
                '',
                array('' => 'choosedots'),
                array('aria-labelledby' => 'moveselectedcategoriesto', 'class' => 'mr-1')
            );
            $submit = array('type' => 'submit', 'name' => 'bulkmovecategories', 'value' => get_string('move'),
                'class' => 'btn btn-secondary');
            $html .= $this->detail_pair(
                html_writer::span(get_string('moveselectedcategoriesto'), '', array('id' => 'moveselectedcategoriesto')),
                $select . html_writer::empty_tag('input', $submit)
            );
        }
        $html .= html_writer::end_div();
        return $html;
    }

    /**
     * Presents a course category listing.
     *
     * @param core_course_category $category The currently selected category. Also the category to highlight in the listing.
     * @return string
     */
    public function category_listing(core_course_category $category = null) {

        if ($category === null) {
            $selectedparents = array();
            $selectedcategory = null;
        } else {
            $selectedparents = $category->get_parents();
            $selectedparents[] = $category->id;
            $selectedcategory = $category->id;
        }
        $catatlevel = \core_course\management\helper::get_expanded_categories('');
        $catatlevel[] = array_shift($selectedparents);
        $catatlevel = array_unique($catatlevel);

        $listing = core_course_category::top()->get_children();

        $attributes = array(
                'class' => 'ml-1 list-unstyled',
                'role' => 'tree',
                'aria-labelledby' => 'category-listing-title'
        );

        $html  = html_writer::start_div('category-listing card w-100');
        $html .= html_writer::tag('h3', get_string('categories'),
                array('class' => 'card-header text-center', 'id' => 'category-listing-title'));
        $html .= html_writer::start_div('card-body p-0');
        $html .= $this->category_listing_actions($category);
        $html .= html_writer::start_tag('ul', $attributes);
        foreach ($listing as $listitem) {
            // Render each category in the listing.
            $subcategories = array();
            if (in_array($listitem->id, $catatlevel)) {
                $subcategories = $listitem->get_children();
            }
            $html .= $this->category_listitem(
                    $listitem,
                    $subcategories,
                    $listitem->get_children_count(),
                    $selectedcategory,
                    $selectedparents
            );
        }
        $html .= html_writer::end_tag('ul');
        $html .= $this->category_bulk_actions($category);
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        return $html;
    }

    /**
     * Renders a course listing.
     *
     * @param core_course_category $category The currently selected category. This is what the listing is focused on.
     * @param core_course_list_element $course The currently selected course.
     * @param int $page The page being displayed.
     * @param int $perpage The number of courses to display per page.
     * @param string|null $viewmode The view mode the page is in, one out of 'default', 'combined', 'courses' or 'categories'.
     * @return string
     */
    public function course_listing(core_course_category $category = null, core_course_list_element $course = null,
            $page = 0, $perpage = 20, $viewmode = 'default') {

        if ($category === null) {
            $html = html_writer::start_div('select-a-category');
            $html .= html_writer::tag('h3', get_string('courses'),
                    array('id' => 'course-listing-title', 'tabindex' => '0'));
            $html .= $this->output->notification(get_string('selectacategory'), 'notifymessage');
            $html .= html_writer::end_div();
            return $html;
        }

        $page = max($page, 0);
        $perpage = max($perpage, 2);
        $totalcourses = $category->coursecount;
        $totalpages = ceil($totalcourses / $perpage);
        if ($page > $totalpages - 1) {
            $page = $totalpages - 1;
        }
        $options = array(
                'offset' => $page * $perpage,
                'limit' => $perpage
        );
        $courseid = isset($course) ? $course->id : null;
        $class = '';
        if ($page === 0) {
            $class .= ' firstpage';
        }
        if ($page + 1 === (int)$totalpages) {
            $class .= ' lastpage';
        }

        $html  = html_writer::start_div('card course-listing w-100'.$class, array(
                'data-category' => $category->id,
                'data-page' => $page,
                'data-totalpages' => $totalpages,
                'data-totalcourses' => $totalcourses,
                'data-canmoveoutof' => $category->can_move_courses_out_of() && $category->can_move_courses_into()
        ));
        $html .= html_writer::tag('h3', $category->get_formatted_name(),
                array('id' => 'course-listing-title', 'tabindex' => '0', 'class' => 'card-header text-center'));
        $html .= html_writer::start_div('card-body p-0');
        $html .= $this->course_listing_actions($category, $course, $perpage);
        $html .= $this->listing_pagination($category, $page, $perpage, false, $viewmode);
        $html .= html_writer::start_tag('ul', array('class' => 'ml course-list p-0', 'role' => 'group'));
        foreach ($category->get_courses($options) as $listitem) {
            $html .= $this->course_listitem($category, $listitem, $courseid);
        }
        $html .= html_writer::end_tag('ul');
        $html .= $this->listing_pagination($category, $page, $perpage, true, $viewmode);
        $html .= $this->course_bulk_actions($category);
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        return $html;
    }

    /**
     * Renderers a course list item.
     *
     * This function will be called for every course being displayed by course_listing.
     *
     * @param core_course_category $category The currently selected category and the category the course belongs to.
     * @param core_course_list_element $course The course to produce HTML for.
     * @param int $selectedcourse The id of the currently selected course.
     * @return string
     */
    public function course_listitem(core_course_category $category, core_course_list_element $course, $selectedcourse) {

        $text = $course->get_formatted_name();
        $attributes = array(
                'class' => 'listitem listitem-course list-group-item list-group-item-action p-2',
                'data-id' => $course->id,
                'data-selected' => ($selectedcourse == $course->id) ? '1' : '0',
                'data-visible' => $course->visible ? '1' : '0'
        );

        $bulkcourseinput = array(
                'type' => 'checkbox',
                'name' => 'bc[]',
                'value' => $course->id,
                'class' => 'bulk-action-checkbox',
                'aria-label' => get_string('bulkactionselect', 'moodle', $text),
                'data-action' => 'select'
        );
        if (!$category->has_manage_capability()) {
            // Very very hardcoded here.
            $bulkcourseinput['style'] = 'visibility:hidden';
        }

        $viewcourseurl = new moodle_url($this->page->url, array('courseid' => $course->id));

        $html  = html_writer::start_tag('li', $attributes);
        $html .= html_writer::start_div('clearfix');

        if ($category->can_resort_courses()) {
            // In order for dnd to be available the user must be able to resort the category children..
            $html .= html_writer::div($this->output->pix_icon('i/move_2d', get_string('dndcourse')), 'float-left drag-handle');
        }

        $html .= html_writer::start_div('ba-checkbox float-left');
        $html .= html_writer::empty_tag('input', $bulkcourseinput).'&nbsp;';
        $html .= html_writer::end_div();
        $html .= html_writer::link($viewcourseurl, $text, array('class' => 'float-left coursename'));
        $html .= html_writer::start_div('float-right');
        if ($course->idnumber) {
            $html .= html_writer::tag('span', s($course->idnumber), array('class' => 'dimmed idnumber'));
        }
        $html .= $this->course_listitem_actions($category, $course);
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        $html .= html_writer::end_tag('li');
        return $html;
    }
}
