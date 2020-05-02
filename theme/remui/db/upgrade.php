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
 * @package     theme_remui
 * @copyright   (c) 2019 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author      Yogesh Shirsath
 */

defined('MOODLE_INTERNAL') || die();

use theme_remui\frontpage\section_manager as section_manager;

/**
 * upgrade this edwiserform plugin database
 * @param int $oldversion The old version of the edwiserform local plugin
 * @return bool
 */
function xmldb_theme_remui_upgrade($oldversion) {
    global $CFG, $DB;
    // return true;
    $dbman = $DB->get_manager();

    if ($oldversion < 2019070203) {

        // Creating theme_remui_section_instance
        $table = new xmldb_table('theme_remui_section_instance');
        $table->addField(new xmldb_field('id', XMLDB_TYPE_INTEGER, 10, null, true, true));
        $table->addField(new xmldb_field('sectid', XMLDB_TYPE_INTEGER, 10, null, true));
        $table->addField(new xmldb_field('name', XMLDB_TYPE_CHAR, 255, null, true));
        $table->addField(new xmldb_field('deleted', XMLDB_TYPE_INTEGER, 1, null, true, null, 0));
        $table->addField(new xmldb_field('visible', XMLDB_TYPE_INTEGER, 1, null, true, null, 1));
        $table->addField(new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, 10, null, true));
        $table->addField(new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, 10, null, true));
        $table->addField(new xmldb_field('configdata', XMLDB_TYPE_TEXT, null, null, false));
        $table->addField(new xmldb_field('draftconfig', XMLDB_TYPE_TEXT, null, null, false));
        $table->addKey(new xmldb_key('primary', XMLDB_KEY_PRIMARY, array('id')));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        set_config('createfrontpagesections', true, 'theme_remui');

        upgrade_plugin_savepoint(true, 2019070203, 'theme', 'remui');
    }
    return true;
}
