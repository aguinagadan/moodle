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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class block_remuiblck_task_popup_form extends moodleform {

    public function __construct($taskid) {
        $this->taskid = $taskid;
        parent::__construct();
    }
    /**
     * Define this form - called from the parent constructor.
     */
    public function definition() {
        $taskhandler = new block_remuiblck_taskhandler($this->taskid);
        $mform = $this->_form;

        $mform->addElement('text', 'subject', get_string('subject', 'block_remuiblck'));
        $mform->addRule('subject', get_string('missingsubject', 'block_remuiblck'), 'required', null, 'client');
        $mform->setType('subject', PARAM_TEXT);

        $mform->addElement('textarea', 'summary', get_string('summary', 'block_remuiblck'), 'wrap="virtual" rows="5" cols="50"');

        $mform->addElement('date_selector', 'due', get_string('duedate', 'block_remuiblck'));

        $users = $taskhandler->get_users();

        $options = array(
            'multiple' => true
        );
        $mform->addElement('autocomplete', 'userlist', get_string('selectusers', 'enrol_manual'), $users, $options);

        $mform->addElement('checkbox', 'visible', get_string('visible'));
        $mform->setDefault('visible', true);

        $mform->addElement('checkbox', 'notify', get_string('notify', 'block_remuiblck'));
    }
}
