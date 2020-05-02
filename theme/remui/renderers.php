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
 * Edwiser RemUI License
 * @package    theme_remui
 * @copyright  (c) 2018 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/backup/util/ui/renderer.php");

class theme_remui_core_backup_renderer extends core_backup_renderer {
    /**
     * Renderers a progress bar for the backup or restore given the items that make it up.
     *
     * @param array $items An array of items
     * @return string
     */
    public function progress_bar(array $items) {
        foreach ($items as &$item) {
            $num = $item['text'][0];
            $text = substr($item['text'], 3);
            unset($item['text']);

            if (strpos($item['class'], "backup_stage_current") !== false ) {
                $item = '<div class="step current " >';
            } else {
                $item = '<div class="step " >';
            }
            $item .= '<span class="step-number">'.$num.'</span>';
            $item .= '<div class="step-desc d-flex align-items-center">';
            $item .= '<span class="step-title font-size-14">'.$text.'</span></div></div>';
        }
        return html_writer::tag('div', join('', $items), array('class' => 'steps row m-0 mb-10'));
    }
}