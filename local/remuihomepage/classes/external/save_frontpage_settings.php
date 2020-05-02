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
use external_value;
use \local_remuihomepage\frontpage\section_manager as section_manager;
use context_system;

trait save_frontpage_settings {
    /**
     * Describes the parameters for save_frontpage_settings
     * @return external_function_parameters
     */
    public static function save_frontpage_settings_parameters() {
        return new external_function_parameters(
            array(
                'settings' => new external_value(PARAM_RAW, 'Json settings form data')
            )
        );
    }

    /**
     * Save frontpage settings
     * @return array sections list
     */
    public static function save_frontpage_settings($settings) {
        $sm = new section_manager();
        $settings = json_decode($settings);
        parse_str($settings, $settings);
        $settings = $sm->convert_to_array($settings);
        $fs = get_file_storage();
        $context = context_system::instance();

        // Save loader image.
        $title = 'frontpageloader';
        $itemid = $settings[$title];
        file_save_draft_area_files($itemid, $context->id, 'theme_remui', $title, 0);
        $files = $fs->get_area_files($context->id, 'theme_remui', $title, 0);
        $loaderimage = "";
        foreach ($files as $file) {
            if ($file->get_filename() != '.') {
                $loaderimage = $file->get_filepath() . $file->get_filename();
                break;
            }
        }
        set_config($title, $loaderimage, 'theme_remui');

        // Save transparent header.
        $title = 'frontpagetransparentheader';
        $transparent = isset($settings[$title]) ? $settings[$title] : false;
        set_config($title, $transparent, 'theme_remui');

        // Save color.
        $title = 'frontpageheadercolor';
        $color = isset($settings[$title]) ? $settings[$title] : '#ffffff';
        set_config($title, $color, 'theme_remui');

        // Save appear animation.
        $title = 'frontpageappearanimation';
        $animation = isset($settings[$title]) ? $settings[$title] : false;
        set_config($title, $animation, 'theme_remui');

        // Save appear animation style.
        $title = 'frontpageappearanimationstyle';
        $animation = isset($settings[$title]) ? $settings[$title] : 'animation-slide-bottom';
        set_config($title, $animation, 'theme_remui');
        return true;
    }

    /**
     * Describes the save_frontpage_settings return value
     * @return external_value
     */
    public static function save_frontpage_settings_returns() {
        return new external_value(PARAM_BOOL, 'Status');
    }
}
