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
 * This file keeps track of upgrades to the navigation block
 *
 * Sometimes, changes between versions involve alterations to database structures
 * and other major things that may break installations.
 *
 * The upgrade function in this file will attempt to perform all the necessary
 * actions to upgrade your older installation to the current version.
 *
 * If there's something it cannot do itself, it will tell you what you need to do.
 *
 * The commands in here will all be database-neutral, using the methods of
 * database_manager class
 *
 * Please do not forget to use upgrade_set_timeout()
 * before any action that may take longer time to finish.
 *
 * @since Moodle 2.0
 * @package block_navigation
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/*
 * As of the implementation of this block and the general navigation code
 * in Moodle 2.0 the body of immediate upgrade work for this block and
 * settings is done in core upgrade {@see lib/db/upgrade.php}
 *
 * There were several reasons that they were put there and not here, both becuase
 * the process for the two blocks was very similar and because the upgrade process
 * was complex due to us wanting to remvoe the outmoded blocks that this
 * block was going to replace.
 *
 * @param int $oldversion
 * @param object $block
 */
require_once($CFG->dirroot. '/config.php');
require_once($CFG->dirroot . '/my/lib.php');
function xmldb_block_remuiblck_upgrade($oldversion, $block) {
    global $CFG, $DB, $CFG;

    $dbman = $DB->get_manager();

    if ($oldversion < 2019052800) {

        // Define table block_remuiblck_tasklist to be created.
        $table = new xmldb_table('block_remuiblck_taskslist');

        // Adding fields to table block_remuiblck_tasklist.
        $table->add_field('id', XMLDB_TYPE_INTEGER, 10, null, true, true);
        $table->add_field('subject', XMLDB_TYPE_CHAR, 500, null, true);
        $table->add_field('summary', XMLDB_TYPE_CHAR, 1000);
        $table->add_field('createdby', XMLDB_TYPE_INTEGER, 10, null, true);
        $table->add_field('assignedto', XMLDB_TYPE_CHAR, 1000);
        $table->add_field('completed', XMLDB_TYPE_INTEGER, 10, null, null, null, 0);
        $table->add_field('deleted', XMLDB_TYPE_INTEGER, 10, null, null, null, 0);
        $table->add_field('notify', XMLDB_TYPE_INTEGER, 10, null, null, null, 0);
        $table->add_field('visible', XMLDB_TYPE_INTEGER, 10, null, true);
        $table->add_field('timedue', XMLDB_TYPE_INTEGER, 10, null, true);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, 10, null, true);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, 10, null, true);

        // Adding keys to table block_remuiblck_taskslist.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for block_remuiblck_taskslist.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Remui Block savepoint reached.
        upgrade_block_savepoint(true, 2019052800, 'remuiblck');
    }

    $block = $block;

    $DB->delete_records('block_instances', array('blockname' => 'remuiblck'));
    $DB->delete_records('user_preferences', array('name' => 'remuiblck_pos_state'));
    $DB->delete_records('config_plugins', array('plugin' => 'block_remuiblck', 'name' => 'blocks_flag_instl'));

    $systempage = $DB->get_record('my_pages', array('userid' => null, 'private' => 1));

    $page = new moodle_page();
    $page->set_context(context_system::instance());

    // selecting default region for blocks i.e. content
    $page->blocks->add_region('content');

    // Adding multiple blocks
    if ($systempage) {
        $page->blocks->add_block('remuiblck', 'content', 5, false, 'my-index', $systempage->id);
    }

    // This will reset the dashboard for everyone using this site.
    my_reset_page_for_all_users(MY_PAGE_PRIVATE, 'my-index');

    // setting flags for blocks addition on dashboard page
    $blockslist = get_default_blocks_list();
    set_config('blocks_list_pos', serialize($blockslist), 'block_remuiblck');

    return true;
}
