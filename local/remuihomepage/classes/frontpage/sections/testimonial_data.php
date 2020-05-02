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

trait testimonial_data {

    /**
     * Returns the team form.
     * @param  stdClass &config data object
     * @return stdClass config data
     */
    private function testimonial_data($configdata) {

        // Multi Lang support for Main Tile, Description and btnlabel.
        if (isset($configdata['title']['text'])) {
            $configdata['title']['text'] = strip_tags(format_text($configdata['title']['text']));
        }
        if (isset($configdata['description']['text'])) {
            $configdata['description']['text'] = strip_tags(format_text($configdata['description']['text']));
        }

        foreach ($configdata['testimonial'] as $key => $value) {
            if (isset($configdata['testimonial'][$key]['name']['text'])) {
                $configdata['testimonial'][$key]['name']['text'] = strip_tags(
                    format_text($configdata['testimonial'][$key]['name']['text'])
                );
            }
            if (isset($configdata['testimonial'][$key]['designation']['text'])) {
                $configdata['testimonial'][$key]['designation']['text'] = strip_tags(
                    format_text($configdata['testimonial'][$key]['designation']['text'])
                );
            }
            if (isset($configdata['testimonial'][$key]['testimonial']['text'])) {
                $configdata['testimonial'][$key]['testimonial']['text'] = strip_tags(
                    format_text($configdata['testimonial'][$key]['testimonial']['text'])
                );
            }
        }

        return $configdata;
    }
}
