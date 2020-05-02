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
use external_value;
use external_multiple_structure;

trait get_quiz_participation {
    /*
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_quiz_participation_parameters() {
        // get_quiz_participation_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            // a external_description can be: external_value, external_single_structure or external_multiple structure
            array(
                'courseid' => new external_value(PARAM_INT, 'Id of course')
            )
        );
    }
    /**
     * The function itself
     * @return string welcome message
     */
    public static function get_quiz_participation($courseid) {
        global $DB;
        $limit = 8;
        $sqlq = ("SELECT COUNT(DISTINCT u.id)
            FROM {course} c
            JOIN {context} ct ON c.id = ct.instanceid
            JOIN {role_assignments} ra ON ra.contextid = ct.id
            JOIN {user} u ON u.id = ra.userid
            JOIN {role} r ON r.id = ra.roleid
            WHERE c.id = ?");
        $totalcount = $DB->get_records_sql($sqlq, array($courseid));
        $totalcount = key($totalcount);
        $sqlq = ("SELECT SUBSTRING(q.name, 1, 20) labels , COUNT(DISTINCT qa.userid) attempts
            FROM {quiz} q
            LEFT JOIN {quiz_attempts} qa ON q.id = qa.quiz
            WHERE q.course = ?
            GROUP BY q.name
            ORDER BY attempts DESC
            LIMIT $limit");
        $quizdata = $DB->get_records_sql($sqlq, array($courseid));
        $chartdata = array();
        $index = 0;
        $chartdata['datasets'][0]['label'] = get_string('totalusersattemptedquiz', 'block_remuiblck');
        $chartdata['datasets'][1]['label'] = get_string('totalusersnotattemptedquiz', 'block_remuiblck');
        $chartdata['datasets'][0]['backgroundColor'] = "rgba(75, 192, 192, 0.2)";
        $chartdata['datasets'][1]['backgroundColor'] = "rgba(255, 99, 132, 0.2)";
        $chartdata['datasets'][0]['borderColor'] = "rgba(75, 192, 192, 1)";
        $chartdata['datasets'][1]['borderColor'] = "rgba(255,99,132,1)";
        $chartdata['datasets'][0]['borderWidth'] = 1;
        $chartdata['datasets'][1]['borderWidth'] = 1;
        foreach ($quizdata as $key => $quiz) {
            $chartdata['labels'][$index] = $key;
            $chartdata['datasets'][0]['data'][$index] = intval($quiz->attempts);
            $chartdata['datasets'][1]['data'][$index] = intval($totalcount - $quiz->attempts);
            if ($chartdata['datasets'][1]['data'][$index] < 0) {
                $chartdata['datasets'][1]['data'][$index] = 0;
            }
            // $quizdata[$key]->noattempts = $totalcount - $quiz->attempts;
            $index++;
        }
        return $chartdata;
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_quiz_participation_returns() {
        return new external_single_structure(
            array(
                'labels' => new external_multiple_structure(
                    new external_value(PARAM_TEXT, 'Labels')
                ),
                'datasets' => new external_multiple_structure(
                    new external_single_structure(array(
                        'label' => new external_value(PARAM_TEXT, 'Bar label'),
                        'backgroundColor' => new external_value(PARAM_TEXT, 'Bar background color'),
                        'borderColor' => new external_value(PARAM_TEXT, 'Bar border color'),
                        'borderWidth' => new external_value(PARAM_TEXT, 'Bar border width'),
                        'data' => new external_multiple_structure(
                            new external_value(PARAM_INT, 'Bar data')
                        )
                    ))
                ),
            )
        );
    }
}
