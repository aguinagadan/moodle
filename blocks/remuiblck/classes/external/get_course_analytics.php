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

require_once($CFG->libdir . '/grade/grade_item.php');
require_once($CFG->libdir . '/grade/grade_grade.php');

use stdClass;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;
use context_course;
use context_system;
use theme_remui\utility as utility;
use block_remuiblck\coursehandler as coursehandler;
use core_completion\progress;

trait get_course_analytics {

    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_course_analytics_parameters() {
        // get_course_analytics_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
                'courseid'   => new external_value(PARAM_INT, 'Course id', VALUE_DEFAULT, 0)
            )
        );
    }



    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_course_analytics($courseid) {
        global $USER;
        if ($courseid == 0) {
            return array(
                'status' => false
            );
        }
        // Get the list of users which are enrolled in the course
        $context = CONTEXT_COURSE::instance($courseid);
        $enrolledusers = get_enrolled_users($context, 'mod/assignment:submit');

        // Get all the activities of the course which can be graded
        $gradeactivities = grade_get_gradable_activities($courseid);
        $qactivity = [];

        if (!empty($gradeactivities)) {
            foreach ($gradeactivities as $gradeactivity) {
                $attempt = 0;
                // Get all the grade items for the activity
                $gradeitemlist = grade_get_grade_items_for_activity($gradeactivity, true);
                $gradeitem = reset($gradeitemlist);

                // Get the last attempt grade value of logged in users
                $grade = new \grade_grade(array('itemid' => $gradeitem->id, 'userid' => $USER->id));
                if (isset($grade->rawgrade)) {
                    $average = intval($grade->rawgrade);
                    $attempt = 1;
                } else {
                    $average = 0;
                }

                // Get the average grade for the activity of last attempt of all enrolled users
                $sum = 0;
                $count = 0;
                foreach ($enrolledusers as $user) {
                    $grade = new \grade_grade(array('itemid' => $gradeitem->id, 'userid' => $user->id));
                    if (isset($grade->rawgrade)) {
                        $sum += intval($grade->rawgrade);
                        $count++;
                    }
                }
                if ($count) {
                    $globalaverage = $sum / $count;
                } else {
                    $globalaverage = 0;
                }
                $qactivity[] = ['id' => $gradeactivity->id, 'name' => $gradeactivity->name, 'lastAttempt' => $average, 'globalAverage' => $globalaverage, 'attempt' => $attempt];
            }
        }

        $chartdata = array();
        $index = 0;
        $chartdata['datasets'][0]['label'] = 'Last Attempt';
        $chartdata['datasets'][1]['label'] = 'Global Average';
        $chartdata['datasets'][0]['backgroundColor'] = "#7dd3ae";
        $chartdata['datasets'][1]['backgroundColor'] = "#a58add";
        $chartdata['labels'] = [];
        foreach ($qactivity as $activity) {
            $chartdata['labels'][$index] = $activity['name'];
            $chartdata['datasets'][0]['data'][$index] = $activity['lastAttempt'];
            $qactivity[$index]['lastAttempt'] = $chartdata['datasets'][0]['data'][$index];
            $chartdata['datasets'][1]['data'][$index] = (int)$activity['globalAverage'];

            if ($chartdata['datasets'][1]['data'][$index] < 0) {
                $chartdata['datasets'][1]['data'][$index] = 0;
            }
            $index++;
        }

        $highest = max(array_column($qactivity, 'lastAttempt'));
        $lowest = min(array_column($qactivity, 'lastAttempt'));
        if (count($qactivity)) {
            $average = intval(array_sum(array_column($qactivity, 'lastAttempt')) / count(array_column($qactivity, 'lastAttempt')));
        } else {
            $average = 0;
        }

        $maxs = array_keys(array_column($qactivity, 'lastAttempt'), $highest);
        $mins = array_keys(array_column($qactivity, 'lastAttempt'), $lowest);

        $maxactivityname = "";
        $minactivityname = "";

        foreach ($maxs as $max) {
            if ($qactivity[$max]['attempt'] == 1) {
                $maxactivityname .= $qactivity[$max]['name'] .", ";
            }
        }

        foreach ($mins as $min) {
            if ($qactivity[$min]['attempt'] == 1) {
                $minactivityname .= $qactivity[$min]['name'] .", ";
            }
        }
        $chartdata['status'] = true;
        $chartdata['highest'] = $highest;
        $chartdata['lowest'] = $lowest;
        $chartdata['average'] = $average;
        $chartdata['maxactivityname'] = rtrim($maxactivityname, ", ");
        $chartdata['minactivityname'] = rtrim($minactivityname, ", ");
        return $chartdata;
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_course_analytics_returns() {
        return new external_single_structure(array(
            'status'   => new external_value(PARAM_BOOL, 'Course analytics data status'),
            'highest'  => new external_value(PARAM_INT, 'Highest grade', VALUE_DEFAULT, 0),
            'lowest'   => new external_value(PARAM_INT, 'Highest grade', VALUE_DEFAULT, 0),
            'average'  => new external_value(PARAM_INT, 'Highest grade', VALUE_DEFAULT, 0),
            'labels' => new external_multiple_structure(
                new external_value(PARAM_TEXT, 'Bar label'),
                'Bar labels',
                VALUE_DEFAULT
            ),
            'datasets' => new external_multiple_structure(
                new external_single_structure(array(
                    'label' => new external_value(PARAM_TEXT, 'Assignment name'),
                    'backgroundColor' => new external_value(PARAM_TEXT, 'Bar color'),
                    'data' => new external_multiple_structure(
                        new external_value(PARAM_INT, 'Bar value'),
                        'Bar dataset',
                        VALUE_DEFAULT
                    )
                )),
                'Chart data',
                VALUE_DEFAULT,
                []
            )
        ));
    }
}