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
 * @package     local_remuihomepage
 * @copyright   (c) 2019 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author      Yogesh Shirsath
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Installation hook for local_remuihomepage plugin
 * @param int $oldversion The old version of the local_remuihomepage local plugin
 * @return bool
 */
function xmldb_local_remuihomepage_install() {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    $skip = false;

    // Define table theme_remui_section_instance to be created.
    $table = new xmldb_table('theme_remui_section_instance');
    if ($dbman->table_exists($table)) {
        $dbman->rename_table($table, 'remuihomepage_sections');
        $skip = true;
    }

    // Define table theme_remui_section_instance to be created.
    $table = new xmldb_table('remui_homepage_sections');
    if ($dbman->table_exists($table)) {
        $dbman->rename_table($table, 'remuihomepage_sections');
        $skip = true;
    }

    if ($skip == true) {
        return true;
    }
    $table = new xmldb_table('remuihomepage_sections');

    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('sectid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
    $table->add_field('deleted', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
    $table->add_field('visible', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
    $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
    $table->add_field('configdata', XMLDB_TYPE_TEXT, '1500', null, false, null, null);
    $table->add_field('draftconfig', XMLDB_TYPE_TEXT, '1500', null, false, null, null);

    // Adding keys to table theme_remui_section_instance.
    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    // Conditionally launch create table for theme_remui_section_instance.
    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }
    return true;
}
