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
defined('SEC_HTML') || define('SEC_HTML', 'section_html');

use context_system;
use \theme_remui\utility as utility;

trait html_data {


    /**
     * Returns the courses form.
     * @param  stdClass &config data object
     * @return stdClass config data
     */
    private function html_data($configdata) {
        global $CFG, $OUTPUT;
        $systemcontext = context_system::instance();
        $count = count($configdata['block']);
        $classname = "html-block p-0 col-12";
        switch ($count) {
            case 2:
                $classname .= " col-lg-6";
                break;
            case 3:
                $classname .= " col-lg-4";
                break;
            case 4:
                $classname .= " col-lg-3 col-md-6";
                break;
        }
        $configdata['classname'] = $classname;
        $styleprefix = ".home-sections [data-instance='" . $configdata['id'] . "'] .html-blocks .html-block:nth-child(";
        foreach ($configdata['block'] as $kblock => $block) {
            $configdata['block'][$kblock]['html']['text'] = file_rewrite_pluginfile_urls(
                $block['html']['text'],
                'pluginfile.php',
                $systemcontext->id,
                'theme_remui',
                SEC_HTML,
                $block['html']['itemid']
            );
            $configdata['block'][$kblock]['style'] = $this->process_css($block['style'], $styleprefix . ($kblock + 1) . ")");
            if (isset($configdata['applyfilter']) && $configdata['applyfilter']) {
                $configdata['block'][$kblock]['html']['text'] = format_text($configdata['block'][$kblock]['html']['text']);
            }
        }
        return $configdata;
    }
}
