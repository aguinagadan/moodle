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
 * @author  2018 wisdmlabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_remuihomepage_create_section_instance' => array(
        'classname'     => 'local_remuihomepage\external\api',
        'methodname'    => 'create_section_instance',
        'description'   => 'Create New Section Instance',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'local_remuihomepage_delete_section_instance' => array(
        'classname'     => 'local_remuihomepage\external\api',
        'methodname'    => 'delete_section_instance',
        'description'   => 'Delete Section Instance',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'local_remuihomepage_fetch_all_instances' => array(
        'classname'     => 'local_remuihomepage\external\api',
        'methodname'    => 'fetch_all_instances',
        'description'   => 'Fetch All Instances',
        'loginrequired' => false,
        'type'          => 'read',
        'ajax'          => true,
    ),
    'local_remuihomepage_get_frontpage_section_courses_in_category' => array(
        'classname'     => 'local_remuihomepage\external\api',
        'methodname'    => 'get_frontpage_section_courses_in_category',
        'description'   => 'Get courses and it\'s data of category',
        'type'          => 'read',
        'loginrequired' => false,
        'ajax'          => true,
    ),
    'local_remuihomepage_save_frontpage_settings' => array(
        'classname'     => 'local_remuihomepage\external\api',
        'methodname'    => 'save_frontpage_settings',
        'description'   => 'Save frontapge settings into plugin config',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'local_remuihomepage_save_sections_order' => array(
        'classname'     => 'local_remuihomepage\external\api',
        'methodname'    => 'save_sections_order',
        'description'   => 'Save order of sections',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'local_remuihomepage_update_section_instance' => array(
        'classname'     => 'local_remuihomepage\external\api',
        'methodname'    => 'update_section_instance',
        'description'   => 'Update Section Instance',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'local_remuihomepage_update_section_visibility' => array(
        'classname'     => 'local_remuihomepage\external\api',
        'methodname'    => 'update_section_visibility',
        'description'   => 'Update Section visibility',
        'type'          => 'write',
        'ajax'          => true,
    ),
    'local_remuihomepage_get_frontpage_categories' => array(
        'classname'     => 'local_remuihomepage\external\api',
        'methodname'    => 'get_frontpage_categories',
        'description'   => 'Get categories for courses section',
        'type'          => 'read',
        'ajax'          => true,
    )
);


