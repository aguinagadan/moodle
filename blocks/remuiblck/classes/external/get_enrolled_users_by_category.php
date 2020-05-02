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

namespace block_remuiblck\external;

use external_function_parameters;
use external_single_structure;
use external_multiple_structure;
use external_value;
use context_course;

trait get_enrolled_users_by_category {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_enrolled_users_by_category_parameters() {
        // get_enrolled_users_by_category_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
                'categoryid'     => new external_value(PARAM_INT, 'Id of category')
            )
        );
    }
    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_enrolled_users_by_category($categoryid) {
        global $DB;
        $catdata = array(
            'status' => false,
            'labels' => [],
            'data' => [],
            'background_color' => '',
            'hoverBackground_color' => ''
        );
        $query = "SELECT id, fullname, shortname FROM {course} WHERE category = " . $categoryid;
        $courselist = $DB->get_records_sql($query);
        if ($courselist) {
            foreach ($courselist as $course) {
                $context = context_course::instance($course->id);
                $query = "SELECT count(u.id) count
                            FROM {role_assignments} a,
                                 {user} u,
                                 {role} r
                          WHERE contextid = ?
                            AND a.roleid = r.id
                            AND a.userid = u.id
                            AND r.archetype LIKE ?";
                $count = $DB->get_records_sql($query, array($context->id, '%student%'));
                $count = key($count);
                $courselist[$course->id]->count = $count;
            }
            usort($courselist, function ($variable1, $variable2) {
                return $variable2->count - $variable1->count;
            });
            $labels = $data = $backgroundcolor = $hoverbackgroundcolor = array();
            $colors = array('#2196f3', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#ffeb3b', '#ff9800', '#f44336', '#9c27b0', '#673ab7', '#3f51b5');
            $others = $otherscount = 0;
            foreach ($courselist as $index => $course) {
                if ($index > 9) {
                    $others = 1;
                    $otherscount += $course->count;
                } else {
                    array_push($labels, $course->shortname);
                    array_push($data, $course->count);
                    array_push($backgroundcolor, $colors[$index]);
                    array_push($hoverbackgroundcolor, $colors[$index]);
                }
            }
            if ($others > 0) {
                array_push($labels, get_string('other', 'moodle'));
                array_push($data, $otherscount);
                array_push($backgroundcolor, $colors[10]);
                array_push($hoverbackgroundcolor, $colors[10]);
            }
            $catdata['status'] = true;
            $catdata['labels'] = $labels;
            $catdata['data'] = $data;
            $catdata['background_color'] = $backgroundcolor;
            $catdata['hoverBackground_color'] = $hoverbackgroundcolor;
        }
        return $catdata;
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_enrolled_users_by_category_returns() {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'Users fetching status'),
                'labels' => new external_multiple_structure(
                    new external_value(PARAM_TEXT, 'Course labels')
                ),
                'data' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'Number of enrolled users')
                ),
                'background_color' => new external_multiple_structure(
                    new external_value(PARAM_TEXT, 'background color')
                ),
                'hoverBackground_color' => new external_multiple_structure(
                    new external_value(PARAM_TEXT, 'hover background color')
                ),
            )
        );
    }
}
