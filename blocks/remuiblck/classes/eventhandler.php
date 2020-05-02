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
 * Renderables class
 * @package block_remuiblck
 * @author  2019 WisdmLabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
class observer
{

    public static function reset_dashboard_eventhandler(\core\event\dashboards_reset  $event) {
        global $DB;

        $event = $event; // This thing is to handle the git warning for unused variables
        $layouttop   = json_decode(get_user_preferences('remui_layout_top'));
        $layoutleft  = json_decode(get_user_preferences('remui_layout_left'));
        $layoutright = json_decode(get_user_preferences('remui_layout_right'));

        $blockslist = array();
        if ($layouttop) {
            foreach ($layouttop as $key => $value) {
                $blockslist[$value] = array('enable' => 0, 'side' => 'top');
            }
        }
        if ($layoutleft) {
            foreach ($layoutleft as $key => $value) {
                $blockslist[$value] = array('enable' => 0, 'side' => 'left');
            }
        }
        if ($layoutright) {
            foreach ($layoutright as $key => $value) {
                $blockslist[$value] = array('enable' => 0, 'side' => 'right');
            }
        }

        // This condition is to handle the exception
        // when another Admin reset dashboard
        // But we have already deleted all the preferences
        if (count($blockslist) != 0) {
            // set_config('blocks_reset_userid', $USER->id, 'block_remuiblck');
            set_config('blocks_list_pos', serialize($blockslist), 'block_remuiblck');
            $sql = 'DELETE FROM {user_preferences} WHERE name IN ("remui_layout_top", "remui_layout_left", "remui_layout_right")';
            $DB->execute($sql);

            set_user_preference('remui_layout_top', json_encode($layouttop));
            set_user_preference('remui_layout_left', json_encode($layoutleft));
            set_user_preference('remui_layout_right', json_encode($layoutright));
        }
    }

}
