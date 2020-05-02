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

trait slider_data {

    /**
     * Returns the courses form.
     * @param  stdClass &config data object
     * @return stdClass config data
     */
    private function slider_data($configdata) {

        foreach ($configdata['slide'] as $key => $slide) {
            // Multi lang support for Heading and Description.
            if (isset($configdata['slide'][$key]['heading'])) {
                $configdata['slide'][$key]['heading'] = format_text($configdata['slide'][$key]['heading']);
            }
            if (isset($configdata['slide'][$key]['description'])) {
                $configdata['slide'][$key]['description'] = format_text($configdata['slide'][$key]['description']);
            }
            if (isset($configdata['slide'][$key]['btnlabel'])) {
                $configdata['slide'][$key]['btnlabel'] = format_text($configdata['slide'][$key]['btnlabel']);
            }
        }

        return $configdata;
    }
}
