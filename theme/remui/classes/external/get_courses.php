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
 * @package theme_remui
 * @author  2019 wisdmlabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_remui\external;

defined('MOODLE_INTERNAL') || die;

use external_function_parameters;
use external_single_structure;
use external_value;

trait get_courses {
    /**
     * Describes the parameters for get_courses
     * @return external_function_parameters
     */
    public static function get_courses_parameters() {
        return new external_function_parameters(
            array (
                'data' => new external_value(PARAM_RAW, 'Courses Params in json.')
            )
        );
    }

    /**
     * Save order of sections in array of configuration format
     * @return boolean true
     */
    public static function get_courses($data) {
        global $OUTPUT, $CFG, $PAGE;
        $wdmdata = json_decode($data);
        // Validation for context is needed.
        $context = \context_system::instance();
        $PAGE->set_context($context);

        $result = \theme_remui\utility::get_course_cards_content($wdmdata);
        $customcat = \core_course_category::user_top();
        $categoryid = $customcat->id;

        if (isset($data->category) && $data->category !== 'all') {
            $categoryid = $data->category;
        }
        $result['hasmanagebutton'] = false;

        $coursecat = \core_course_category::get($categoryid);
        if ($coursecat->can_create_course() || $coursecat->has_manage_capability()) {
            $managebutton = $OUTPUT->single_button(new \moodle_url(
                '/course/management.php',
                array('categoryid' => $coursecat->id)
            ), get_string('managecourses'), 'get');

            if ($coursecat->id) {
                $url = new \moodle_url ('/course/edit.php', array('category' => $coursecat->id, 'returnto' => 'category'));
            } else {
                $url = new \moodle_url ('/course/edit.php',
                array('category' => $CFG->defaultrequestcategory, 'returnto' => 'topcat'));
            }
            $managebutton = $OUTPUT->single_button($url, get_string('addnewcourse'), 'get') . $managebutton;
            $result['hasmanagebutton'] = true;
            $result['managebuttons'] = str_replace('type="submit"', 'type="submit" class="btn btn-inverse ml-2"', $managebutton);
        }
        return(json_encode($result));
    }

    /**
     * Describes the get_courses return value
     * @return external_value
     */
    public static function get_courses_returns() {
        return new external_value(PARAM_RAW, 'Courses and Pgination in JSON Format');
    }
}
