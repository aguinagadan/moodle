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
 * Local Terminologia Upgrade
 */


defined('MOODLE_INTERNAL') || die;

/**
 *
 * This is to upgrade the older versions of the plugin.
 *
 * @param integer $oldversion
 * @return bool
 * @copyright   2017 LearningWorks Ltd
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function xmldb_local_terminologia_upgrade($oldversion) {
	global $DB;
	$dbman = $DB->get_manager();

//	if ($oldversion < 205000000) {
		$table = new xmldb_table('termino');
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('nombre', XMLDB_TYPE_TEXT, null, null, null, null);
		$table->add_field('origen', XMLDB_TYPE_TEXT, null, null, null, null);
		$table->add_field('descripcion', XMLDB_TYPE_TEXT, null, null, null, null);
		$table->add_field('imageurl', XMLDB_TYPE_TEXT, null, null, null, null);
		$table->add_field('visitas', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
		$table->add_field('creado', XMLDB_TYPE_INTEGER, '10', null, null, null);
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}

		$table = new xmldb_table('termino_sugerido');
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('nombre', XMLDB_TYPE_TEXT, null, null, null, null);
		$table->add_field('cantidad', XMLDB_TYPE_INTEGER, '10', null, null, null, '1');
		$table->add_field('creado', XMLDB_TYPE_INTEGER, '10', null, null, null);
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}

		$table = new xmldb_table('termino_buscado');
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('id_termino', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('creado', XMLDB_TYPE_INTEGER, '10', null, null, null);
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}

		$table = new xmldb_table('termino_usuario_log');
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('id_usuario', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('creado', XMLDB_TYPE_INTEGER, '10', null, null, null);
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}

		// Local terminologia savepoint reached.
		upgrade_plugin_savepoint(true, 2019011100, 'local', 'terminologia');
	//}

	return true;
}