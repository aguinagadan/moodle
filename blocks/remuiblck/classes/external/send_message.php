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
 * @package theme_remui
 * @author  2019 wisdmlabs
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace block_remuiblck\external;

defined('MOODLE_INTERNAL') || die;

use external_function_parameters;
use external_value;
use context_system;

trait send_message {
    /**
     * Describes the parameters for send_message
     * @return external_function_parameters
     */
    public static function send_message_parameters() {
        return new external_function_parameters(
            array (
                'studentid' => new external_value(PARAM_RAW, 'Config Name'),
                'messagetext' => new external_value(PARAM_RAW, 'Config Value')
            )
        );
    }

    /**
     * Save order of sections in array of configuration format
     * @return boolean true
     */
    public static function send_message($studentid, $messagetext) {
        global $USER, $DB, $SITE, $PAGE;
        $PAGE->set_context(context_system::instance());
        $userfrom = $DB->get_record('user', array('id' => $USER->id), '*', MUST_EXIST);
        $userto = $DB->get_record('user', array('id' => $studentid), '*', MUST_EXIST);

        $message = new \core\message\message();
        $message->courseid = $SITE->id;
        $message->component = 'moodle';
        $message->name = 'instantmessage';
        $message->userfrom = $userfrom;
        $message->userto = $userto;
        $message->subject = '';
        $message->fullmessage = strip_tags($messagetext);
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->fullmessagehtml = $messagetext;
        $message->smallmessage = $messagetext;
        $message->notification = '0';
        $message->contexturl = '';
        $message->contexturlname = '';
        $message->replyto = $userfrom->email;
        $messageid = message_send($message);
        return $messageid;
    }

    /**
     * Describes the send_message return value
     * @return external_value
     */
    public static function send_message_returns() {
        return new external_value(PARAM_INT, 'Message id');
    }
}
