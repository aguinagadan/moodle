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

trait team_data {

    /**
     * Returns the team form.
     * @param  stdClass &config data object
     * @return stdClass config data
     */
    private function team_data($configdata) {

        // Multi Lang support for Main Tile, Description and btnlabel.
        if (isset($configdata['title']['text'])) {
            $configdata['title']['text'] = strip_tags(format_text($configdata['title']['text']));
        }
        if (isset($configdata['description']['text'])) {
            $configdata['description']['text'] = strip_tags(format_text($configdata['description']['text']));
        }

        foreach ($configdata['row'] as $krow => $row) {
            foreach ($configdata['row'][$krow]['member'] as $kf => $member) {

                // Multilang support for each member (Name and description).
                if (isset($configdata['row'][$krow]['member'][$kf]['name']['text'])) {
                    $configdata['row'][$krow]['member'][$kf]['name']['text'] = strip_tags(
                        format_text($configdata['row'][$krow]['member'][$kf]['name']['text'])
                    );
                }

                if (isset($configdata['row'][$krow]['member'][$kf]['quote']['text'])) {
                    $configdata['row'][$krow]['member'][$kf]['quote']['text'] = strip_tags(
                        format_text($configdata['row'][$krow]['member'][$kf]['quote']['text'])
                    );
                }
            }
        }
        return $configdata;
    }
}
