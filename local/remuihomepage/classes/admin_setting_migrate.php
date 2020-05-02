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
 * Edwiser RemUI Homepage Builder
 * @package    local_remuihomepage
 * @copyright  (c) 2019 WisdmLabs (https://wisdmlabs.com/)
 * @author     Yogesh Shirsath
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/adminlib.php');

/**
 * Extension to show an error message if the selected predictor is not available.
 *
 * @package    local_remuihomepage
 * @copyright  (c) 2019 WisdmLabs (https://wisdmlabs.com/)
 * @author     Yogesh Shirsath
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_remuihomepage_admin_setting_migrate extends \admin_setting {
    protected $title;
    protected $url;
    /**
     * Config text constructor
     *
     * @param string $name unique ascii name, either 'mysetting' for settings that in config, or 'myplugin/mysetting' for ones in config_plugins.
     * @param string $visiblename localised
     * @param string $description long localised info
     * @param string $defaultsetting
     */
    public function __construct($title, $url, $description) {
        $this->title = $title;
        $this->url = $url;
        parent::__construct('migrate', '', $description, '');
    }

    /**
     * Return an XHTML string for the setting
     * @return string Returns an XHTML string
     */
    public function output_html($data, $query='') {
        global $OUTPUT;

        $context = (object) [
            'name' => $this->name,
            'title' => $this->title,
            'url' => $this->url,
            'description' => $this->description
        ];
        $element = $OUTPUT->render_from_template('local_remuihomepage/setting_submit_form', $context);

        // return format_admin_setting($this, $this->visiblename, $element, $this->description, true, '', '', $query);
        return $element;
    }

    /**
     * Always returns true
     * @return bool Always returns true
     */
    public function get_setting() {
        return true;
    }

    /**
     * Never write settings
     * @return string Always returns an empty string
     */
    public function write_setting($data) {
        // do not write any setting
        return '';
    }
}
