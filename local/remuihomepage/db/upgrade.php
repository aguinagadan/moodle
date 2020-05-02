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
 * upgrade this remuihomepage plugin database
 * @param int $oldversion The old version of the remuihomepage local plugin
 * @return bool
 */
function xmldb_local_remuihomepage_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    $table = new xmldb_table('remuihomepage_sections');

    if ($dbman->table_exists($table)) {
        $field = new xmldb_field('configdata', XMLDB_TYPE_TEXT, '1500', null, false, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_notnull($table, $field);
        } else {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('draftconfig', XMLDB_TYPE_TEXT, '1500', null, false, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_notnull($table, $field);
        } else {
            $dbman->add_field($table, $field);
        }
    }
    return true;
}
