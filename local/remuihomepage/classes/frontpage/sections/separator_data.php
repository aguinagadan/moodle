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
use \theme_remui\utility as utility;
use local_remuihomepage\frontpage\sections\main_form as main_form;

trait separator_data {


    /**
     * Process separator configuration before rendering it.
     * @param  stdClass &config data object
     * @return stdClass config data
     */
    private function separator_data($configdata) {
        global $CFG, $OUTPUT;
        if ($configdata['deleted'] == true) {
            $configdata['css'] .= ' margin: 150px auto;';
        }
        return $configdata;
    }
}
