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
 * Class qtype_multianswer_multiresponse_renderer
 *
 * @copyright  2016 Davo Smith, Synergy Learning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class multiresponse_vertical_renderer extends \qtype_multianswer_multiresponse_vertical_renderer {

    /**
     * Output the content of the subquestion.
     *
     * @param question_attempt $qa
     * @param question_display_options $options
     * @param int $index
     * @param question_graded_automatically $subq
     * @return string
     */
    public function subquestion(question_attempt $qa, question_display_options $options,
                                $index, question_graded_automatically $subq) {

        if (!$subq instanceof qtype_multichoice_multi_question) {
            throw new coding_exception('Expecting subquestion of type qtype_multichoice_multi_question');
        }

        $fieldprefix = 'sub' . $index . '_';
        $fieldname = $fieldprefix . 'choice';

        // Extract the responses that related to this question + strip off the prefix.
        $fieldprefixlen = strlen($fieldprefix);
        $response = [];
        foreach ($qa->get_last_qt_data() as $name => $val) {
            if (substr($name, 0, $fieldprefixlen) == $fieldprefix) {
                $name = substr($name, $fieldprefixlen);
                $response[$name] = $val;
            }
        }

        $basename = $qa->get_qt_field_name($fieldname);
        $inputattributes = array(
            'type' => 'checkbox',
            'value' => 1,
        );
        if ($options->readonly) {
            $inputattributes['disabled'] = 'disabled';
        }

        $result = $this->all_choices_wrapper_start();

        // Calculate the total score (as we need to know if choices should be marked as 'correct' or 'partial').
        $fraction = 0;
        foreach ($subq->get_order($qa) as $value => $ansid) {
            $ans = $subq->answers[$ansid];
            if ($subq->is_choice_selected($response, $value)) {
                $fraction += $ans->fraction;
            }
        }
        // Display 'correct' answers as correct, if we are at 100%, otherwise mark them as 'partial'.
        $answerfraction = ($fraction > 0.999) ? 1.0 : 0.5;

        foreach ($subq->get_order($qa) as $value => $ansid) {
            $ans = $subq->answers[$ansid];

            $name = $basename.$value;
            $inputattributes['name'] = $name;
            $inputattributes['id'] = $name;

            $isselected = $subq->is_choice_selected($response, $value);
            if ($isselected) {
                $inputattributes['checked'] = 'checked';
            } else {
                unset($inputattributes['checked']);
            }

            $class = 'r' . ($value % 2).' checkbox-custom checkbox-primary '; // our custom checkbox style - bharat - remui
            if ($options->correctness && $isselected) {
                $thisfrac = ($ans->fraction > 0) ? $answerfraction : 0;
                $feedbackimg = $this->feedback_image($thisfrac);
                $class .= ' ' . $this->feedback_class($thisfrac);
            } else {
                $feedbackimg = '';
            }

            $result .= $this->choice_wrapper_start($class);
            $result .= html_writer::empty_tag('input', $inputattributes);
            $result .= html_writer::tag('label', $subq->format_text($ans->answer,
                                                                    $ans->answerformat, $qa, 'question', 'answer', $ansid),
                                        array('for' => $inputattributes['id'], 'class' => "d-block ml-20 mr-25"));
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
            $correct = [];
            foreach ($subq->answers as $ans) {
                if (question_state::graded_state_for_fraction($ans->fraction) != question_state::$gradedwrong) {
                    $correct[] = $subq->format_text($ans->answer, $ans->answerformat, $qa, 'question', 'answer', $ans->id);
                }
            }
            $correct = '<ul><li>'.implode('</li><li>', $correct).'</li></ul>';
            $feedback[] = get_string('correctansweris', 'qtype_multichoice', $correct);
        }

        $result .= html_writer::nonempty_tag('div', implode('<br />', $feedback), array('class' => 'outcome'));

        return $result;
    }
}
