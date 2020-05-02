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

namespace local_remuihomepage\frontpage\sections;
defined('MOODLE_INTERNAL') || die();

use context_system;
use context_course;
use context_coursecat;
use core_course\external\course_summary_exporter as cme;
use \theme_remui\utility as utility;

if (!class_exists('core_course_category')) {
    require_once($CFG->libdir. '/coursecatlib.php');
}
trait courses_data {


    /**
     * Get all details of categories
     * @param  array   $categories Array of categories
     * @param  integer $start      Start index of categories
     * @param  integer $limit      Number of categories to be fetched
     * @return array               Array of categories with category details
     */
    public function get_category_details($categories, $start = 0, $limit = 20) {
        global $OUTPUT, $CFG, $USER;
        $result = array();

        $activeflag = true;

        $total = count($categories);

        $categories = array_slice($categories, $start, $limit);

        foreach ($categories as $key => $category) {
            $rescategories = array();
            if ($activeflag) {
                $rescategories['active'] = true;
                $activeflag = false;
            }
            if (class_exists('core_course_category')) {
                $category = \core_course_category::get($category, MUST_EXIST, true);
            } else if ('coursecat') {
                $category = \coursecat::get($category, MUST_EXIST, true);
            } else {
                continue;
            }

            if ( !$category->visible ) {
                $rescategories['ishidden'] = true;
            }

            $rescategories['categoryid'] = $category->id;
            $rescategories['categoryname'] = strip_tags(format_text($category->name));
            $catsummary = strip_tags(format_text($category->description));
            $catsummary = preg_replace('/\n+/', '', $catsummary);
            $catsummary = strlen($catsummary) > 150 ? mb_substr($catsummary, 0, 150) . "..." : $catsummary;
            $rescategories['categorydesc'] = $catsummary;
            $rescategories['categoryurl'] = $CFG->wwwroot. '/course/index.php?categoryid=' . $category->id;
            $count = \theme_remui\utility::get_courses(true, null, $category->id, 0, 0, null, null);
            $rescategories['coursecount'] = $count;

            if ($category->idnumber != '') {
                $rescategories['idnumber'] = $category->idnumber;
            }

            if ($category->parent != "0") {
                if (class_exists('core_course_category')) {
                    $parent = \core_course_category::get($category->parent);
                } else if (class_exists('coursecat')) {
                    $parent = \coursecat::get($category->parent);
                } else {
                    continue;
                }
                $rescategories['parentname'] = $parent->name;
            }
            $result[] = $rescategories;
        }
        return [$total, $result];
    }

    /**
     * Get all courses from selected categories and date filter
     * @param  array  $categories Categories list
     * @param  string $date       Date filter
     * @return array              Array of course
     */
    public function get_courses_from_category($categories, $date, $start = 0) {
        global $CFG;

        // Retrieve list of courses in category.
        $coursehandler = new \theme_remui_coursehandler();
        $where = 'WHERE c.id <> :siteid ';
        $params = array('siteid' => SITEID);
        $join = '';
        $sesskey = strtolower(sesskey());
        $cattable = 'catids' . $sesskey;
        if (is_numeric($categories) || is_array($categories)) {
            if (is_numeric($categories)) {
                $categories = utility::get_allowed_categories($categories);
            } else {
                $categories = $this->get_categories($categories);
            }
            if (!empty($categories)) {
                $coursehandler->create_temp_table($cattable, $categories);
                $join = " INNER JOIN {$CFG->prefix}$cattable catids ON c.category = catids.id";
            }
        }

        if (!empty($search)) {
            $search = '%' . str_replace(' ', '%', $search) . '%';
            $where .= " AND ( LOWER(c.fullname) like LOWER(:name1) OR LOWER(c.shortname) like LOWER(:name2) )";
            $params = $params + array("name1" => $search, "name2" => $search);
        }
        // Get list of courses without preloaded coursecontacts because we don't need them for every course.

        list($total, $courses) = $coursehandler->get_course_records(
            $where,
            $join,
            $params,
            [
                'summary' => true,
                'filtermodified' => true,
                'limitfrom' => $start,
                'limitto' => 25,
                'totalcount' => true
            ]
        );
        if (is_numeric($categories) || is_array($categories)) {
            $coursehandler->drop_table($cattable);
        }

        if (empty($courses)) {
            return [0, []];
        }

        $obj = new \stdClass;
        $obj->sort = null;
        $obj->search = "";
        $obj->tab = false;
        $obj->page = (Object)['courses' => -1];
        $obj->pagination = false;
        $obj->view = null;
        $obj->isFilterModified = "0";
        $obj->limiteddata = true;
        $obj->courses = $courses;
        $obj->category = 0;
        $obj->limiteddata = true;

        $result = \theme_remui\utility::get_course_cards_content($obj, $date);

        return array($total, $result['courses']);
    }

    /**
     * Get categories list from user settings
     * @param  array $configdata Config data array
     * @return array             Array of categories
     */
    public function get_categories($categories) {
        global $DB;
        if (empty($categories)) {
            return \theme_remui\utility::get_allowed_categories('all');
        }
        foreach ($categories as $category) {
            $cats = \theme_remui\utility::get_allowed_categories($category);
            foreach ($cats as $cat) {
                if (!in_array($cat, $categories)) {
                    array_push($categories, $cat);
                }
            }
        }
        return $categories;
    }

    /**
     * Returns the courses form.
     * @param  stdClass &config data object
     * @return stdClass config data
     */
    private function courses_data($configdata) {
        global $CFG, $OUTPUT, $DB;
        // Multi Lang support for Main Tile, Description and btnlabel.
        if (isset($configdata['title']['text'])) {
            $configdata['title']['text'] = strip_tags(format_text($configdata['title']['text']));
        }
        if (isset($configdata['description']['text'])) {
            $configdata['description']['text'] = strip_tags(format_text($configdata['description']['text']));
        }
        $result = [];
        $categories = $this->get_categories(isset($configdata['categories']) ? $configdata['categories'] : []);
        $date = isset($configdata['date']) ? $configdata['date'] : 'all';

        $type = $configdata['show'];
        switch ($type) {
            case 'courses':
                list($configdata['totalcourses'], $configdata['courses']) = $this->get_courses_from_category($categories, $date);
                if (empty($configdata['courses'])) {
                    $configdata['coursesplaceholder'] = $OUTPUT->image_url('courses', 'block_myoverview')->out();
                }
                break;

            case 'categories':
                list($configdata['categorycount'], $configdata['categorylist']) = $this->get_category_details($categories);
                $configdata['totalcourses'] = 0;
                break;

            case 'categoryandcourses':
                $configdata['categoryandcourses'] = true;
                if (\theme_remui\toolbox::get_setting('enablenewcoursecards')) {
                    $configdata['latest_card'] = true;
                }
                list($configdata['categorycount'], $configdata['categorylist']) = $this->get_category_details($categories);
                $configdata['totalcourses'] = 0;
                break;

            default:
                break;
        }
        $configdata[$type.'view'] = true;

        if (\theme_remui\toolbox::get_setting('enablenewcoursecards')) {
            $configdata['latest_card'] = true;
        }

        $configdata['sectionpropertiesoutput'] = str_replace(
            "class=\"", "class=\"" . $type . "-view ",
            $configdata['sectionpropertiesoutput']
        );

        $configdata['shadowless'] = $configdata['sectionproperties']['shadowless'];
        return $configdata;
    }

}
