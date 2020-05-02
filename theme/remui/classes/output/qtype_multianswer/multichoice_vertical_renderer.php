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
 * Multianswer question renderer classes.
 * Handle shortanswer, numerical and various multichoice subquestions
 *
 * @package    qtype
 * @subpackage multianswer
 * @copyright  2010 Pierre Pichet
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui\output\qtype_multianswer;
use question_attempt;
use question_display_options;
use question_graded_automatically;
use html_writer;
use stdClass;
use question_state;
use qtype_multichoice_multi_question;
use coding_exception;

require_once($CFG->dirroot . '/question/type/shortanswer/renderer.php');

/**
 * Class qtype_multianswer_multichoice_renderer
 *
 * @copyright  2016 Davo Smith, Synergy Learning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class multichoice_vertical_renderer extends \qtype_multianswer_multichoice_vertical_renderer {

    public function subquestion(question_attempt $qa, question_display_options $options,
            $index, question_graded_automatically $subq) {

        $fieldprefix = 'sub' . $index . '_';
        $fieldname = $fieldprefix . 'answer';
        $response = $qa->get_last_qt_var($fieldname);

        $inputattributes = array(
            'type' => 'radio',
            'name' => $qa->get_qt_field_name($fieldname),
        );
        if ($options->readonly) {
            $inputattributes['disabled'] = 'disabled';
        }

        $result = $this->all_choices_wrapper_start();
        $fraction = null;
        foreach ($subq->get_order($qa) as $value => $ansid) {
            $ans = $subq->answers[$ansid];

            $inputattributes['value'] = $value;
            $inputattributes['id'] = $inputattributes['name'] . $value;

            $isselected = $subq->is_choice_selected($response, $value);
            if ($isselected) {
                $inputattributes['checked'] = 'checked';
                $fraction = $ans->fraction;
            } else {
                unset($inputattributes['checked']);
            }

            $class = 'r' . ($value % 2).' radio-custom radio-primary ';
            if ($options->correctness && $isselected) {
                $feedbackimg = $this->feedback_image($ans->fraction);
                $class .= ' ' . $this->feedback_class($ans->fraction);
            } else {
                $feedbackimg = '';
            }

            $result .= $this->choice_wrapper_start($class);
            $result .= html_writer::empty_tag('input', $inputattributes);
            $result .= html_writer::tag('label', $subq->format_text($ans->answer,
                    $ans->answerformat, $qa, 'question', 'answer', $ansid),
                    array('for' => $inputattributes['id'], 'class' => "d-inline-block ml-15 mr-20"));
            $result .= $feedbackimg;

            if ($options->feedback && $isselected && trim($ans->feedback)) {
                $result .= html_writer::tag('div',
                        $subq->format_text($ans->feedback, $ans->feedbackformat,
                                $qa, 'question', 'answerfeedback', $ansid),
                        array('class' => 'specificfeedback'));
            }

            $result .= $this->choice_wrapper_end();
        }

        $result .= $this->all_choices_wrapper_end();

        $feedback = array();
        if ($options->feedback && $options->marks >= question_display_options::MARK_AND_MAX &&
                $subq->maxmark > 0) {
            $a = new stdClass();
            $a->mark = format_float($fraction * $subq->maxmark, $options->markdp);
            $a->max = format_float($subq->maxmark, $options->markdp);

            $feedback[] = html_writer::tag('div', get_string('markoutofmax', 'question', $a));
        }

        if ($options->rightanswer) {
            foreach ($subq->answers as $ans) {
                if (question_state::graded_state_for_fraction($ans->fraction) ==
                        question_state::$gradedright) {
                    $feedback[] = get_string('correctansweris', 'qtype_multichoice',
                            $subq->format_text($ans->answer, $ans->answerformat,
                                    $qa, 'question', 'answer', $ansid));
                    break;
                }
            }
        }

        $result .= html_writer::nonempty_tag('div', implode('<br />', $feedback), array('class' => 'outcome'));

        return $result;
    }
}
