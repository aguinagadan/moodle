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

require_once($CFG->libdir . "/externallib.php");
require_once($CFG->libdir . "/filelib.php");
require_once($CFG->dirroot . "/theme/remui/lib.php");

use external_api;

/**
 * Uses all moodle webservices trait defined in external folder
 */
class api extends external_api {
    use create_section_instance;
    use delete_section_instance;
    use fetch_all_instances;
    use get_frontpage_section_courses_in_category;
    use save_frontpage_settings;
    use save_sections_order;
    use update_section_instance;
    use update_section_visibility;
    use get_frontpage_categories;
}
