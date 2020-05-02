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

trait set_setting {
    /**
     * Describes the parameters for set_setting
     * @return external_function_parameters
     */
    public static function set_setting_parameters() {
        return new external_function_parameters(
            array (
                'configname' => new external_value(PARAM_RAW, 'Config Name'),
                'configvalue' => new external_value(PARAM_RAW, 'Config Value')
            )
        );
    }

    /**
     * Save order of sections in array of configuration format
     * @return boolean true
     */
    public static function set_setting($configname, $configvalue) {
        global $PAGE;
        // Validation for context is needed.
        $context = \context_system::instance();
        self::validate_context($context);

        $theme = 'theme_'.$PAGE->theme->name;
        set_config($configname, $configvalue, $theme);
        return true;
    }

    /**
     * Describes the set_setting return value
     * @return external_value
     */
    public static function set_setting_returns() {
        return new external_value(PARAM_BOOL, 'Status');
    }
}
