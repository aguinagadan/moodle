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
 * @package local_remuihomepage
 * @author  2019 wisdmlabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_remuihomepage\external;

defined('MOODLE_INTERNAL') || die;

use external_function_parameters;
use external_multiple_structure;
use external_value;
use \local_remuihomepage\frontpage\section_manager as section_manager;

trait save_sections_order {
    /**
     * Describes the parameters for save_sections_order
     * @return external_function_parameters
     */
    public static function save_sections_order_parameters() {
        return new external_function_parameters(array(
            'order' => new external_multiple_structure(
                new external_value(PARAM_INT, 'Section instance id'),
                'Section instance ids array',
                VALUE_OPTIONAL,
                []
            )
        ));
    }

    /**
     * Save order of sections in array of configuration format
     * @return boolean true
     */
    public static function save_sections_order($order) {
        $sm = new section_manager();
        $sm->save_sections_order($order);
        return true;
    }

    /**
     * Describes the save_sections_order return value
     * @return external_value
     */
    public static function save_sections_order_returns() {
        return new external_value(PARAM_BOOL, 'Status');
    }
}
