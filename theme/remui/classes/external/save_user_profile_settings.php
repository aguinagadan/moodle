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
use external_value;

trait save_user_profile_settings {
    /**
     * Describes the parameters for save_user_profile_settings
     * @return external_function_parameters
     */
    public static function save_user_profile_settings_parameters() {
        return new external_function_parameters(
            array(
                'fname' => new external_value(PARAM_TEXT, 'Firstname'),
                'lname' => new external_value(PARAM_TEXT, 'Lastname'),
                'description' => new external_value(PARAM_TEXT, 'Description'),
                'city' => new external_value(PARAM_TEXT, 'City'),
                'country' => new external_value(PARAM_ALPHAEXT, 'Country')
            )
        );
    }

    /**
     * Save user profile settings submitted in the profile page
     * @param  String $fname       Firstname
     * @param  String $lname       Lastname
     * @param  String $emailid     Email
     * @param  String $description Description
     * @param  String $city        City
     * @param  String $country     Country
     * @return mixxed              Result
     */
    public static function save_user_profile_settings($fname, $lname, $description, $city, $country) {
        return array('success' => \theme_remui\usercontroller::save_user_profile_info(
            $fname,
            $lname,
            $description,
            $city,
            $country)
        );
    }

    /**
     * Describes the save_user_profile_settings return value
     * @return exernal_function_parameter
     */
    public static function save_user_profile_settings_returns() {
        return new external_function_parameters(
            array(
                'success' => new external_value(PARAM_BOOL, 'Updation success - true/false')
            )
        );
    }
}
