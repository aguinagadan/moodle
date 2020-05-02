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

trait aboutus_data {

    /**
     * Returns the aboutus form.
     * @param  stdClass &config data object
     * @return stdClass config data
     */
    private function aboutus_data($configdata) {

        // Multi Lang support for Main Tile, Description and btnlabel.
        if (isset($configdata['title']['text'])) {
            $configdata['title']['text'] = strip_tags(format_text($configdata['title']['text']));
        }
        if (isset($configdata['description']['text'])) {
            $configdata['description']['text'] = strip_tags(format_text($configdata['description']['text']));
        }
        if (isset($configdata['btnlabel'])) {
            $configdata['btnlabel'] = strip_tags(format_text($configdata['btnlabel']));
        }

        foreach ($configdata['block'] as $key => $block) {
            // Multi lang support for each block.
            if (isset($configdata['block'][$key]['title']['text'])) {
                $configdata['block'][$key]['title']['text'] = strip_tags(format_text($configdata['block'][$key]['title']['text']));
            }
            if (isset($configdata['block'][$key]['description']['text'])) {
                $configdata['block'][$key]['description']['text'] = strip_tags(
                    format_text($configdata['block'][$key]['description']['text'])
                );
            }
            if (isset($configdata['block'][$key]['btnlabel']['text'])) {
                $configdata['block'][$key]['btnlabel']['text'] = strip_tags(
                    format_text($configdata['block'][$key]['btnlabel']['text'])
                );
            }
        }

        return $configdata;
    }
}
