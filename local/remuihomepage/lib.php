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
 * Theme functions.
 *
 * @package    local_remuihomepage
 * @copyright  (c) 2019 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Get section form from fragment
 *
 * @param  array $args Argument passed with fragment call
 *
 * @return string      Frontpage settings form's html output
 */
function local_remuihomepage_output_fragment_frontpage_section_form($args) {
    global $CFG;

    $args = (object) $args;
    $mform = new local_remuihomepage\frontpage\sections\main_form(null, $args);
    ob_start();
    $mform->display();
    $o = ob_get_contents();
    ob_end_clean();

    return $o;
}

/**
 * This function will generate frontpage settings form and return it's html view
 *
 * @param  array $args Argument passed with fragment call
 *
 * @return string      Frontpage settings form's html output
 */
function local_remuihomepage_output_fragment_frontpage_settings_form($args) {
    global $CFG;

    $configdata = [];
    $configdata['frontpageloader'] = get_config('theme_remui', 'frontpageloader');
    $configdata['frontpagetransparentheader'] = get_config('theme_remui', 'frontpagetransparentheader');
    $configdata['frontpageheadercolor'] = get_config('theme_remui', 'frontpageheadercolor');
    $configdata['frontpageappearanimation'] = get_config('theme_remui', 'frontpageappearanimation');
    $configdata['frontpageappearanimationstyle'] = get_config('theme_remui', 'frontpageappearanimationstyle');
    $args['configdata'] = $configdata;

    $args = (object) $args;
    $mform = new local_remuihomepage\frontpage\settings(null, $args);

    return $mform->render();
}
