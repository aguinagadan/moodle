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
 * @package block_remuiblck
 * @author  2019 WisdmLabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot. '/config.php');
require_once($CFG->dirroot . '/my/lib.php');

function xmldb_block_remuiblck_install() {
    global $DB;

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
