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

namespace local_remuihomepage\frontpage\sections;

defined('MOODLE_INTERNAL') || die();
define('SEC_SEPARATOR', 'section_separator');
use html_writer;

trait separator_form {

    /**
     * Populate and return the style for hr tag
     * @param  array $formdata section configuration
     * @return string          the style for hr tag
     */
    public function separator_style($formdata) {
        $style = '';
        switch ($formdata['style']) {
            case 'blur':
                $style .= "border: 1px solid {$formdata['color']};
                box-shadow: 0 0 {$formdata['height']}px " . $formdata['height'] / 2 . "px {$formdata['color']};";
                break;
            case 'blurend':
                $style .= "border: none;
                height: {$formdata['height']}px;
                background-image: linear-gradient(
                    to right,
                    transparent,
                    {$formdata['color']},
                    transparent
                );";
                break;
            case 'gradient':
                $style .= "height: {$formdata['height']}px;
                border: none;
                background: linear-gradient(
                    to right,
                    {$formdata['color']} 0%,
                    {$formdata['color2']} 50%,
                    {$formdata['color']} 100%
                );";
                break;
            default:
                $style .= "border-top: {$formdata['height']}px {$formdata['style']} {$formdata['color']};";
                break;
        }
        $style .= "margin: 0px auto; width: {$formdata['width']}%;";
        return $style;
    }

    /**
     * Return html tag for the separator to show in form
     * @param  array $formdata form data array
     * @return string          Separator output
     */
    private function separator_output($formdata) {
        $style = $this->separator_style($formdata);
        $out = html_writer::start_tag('div', array('class' => 'pt-2'));
        $out .= html_writer::tag('h5', get_string('separatorresult', COMPONENT).':');
        $out .= html_writer::tag('hr', '', array(
            'id' => 'remui_separator_output',
            'style' => $style
        ));
        $out .= html_writer::end_tag('div');
        return $out;
    }

    /**
     * Returns the Separator form.
     * @param  stdClass &$mform   Form object.
     * @param  array    $formdata Form data.
     * @param  string   $config   Saved configuration
     * @return stdClass           Form object with data.
     */
    private function separatorform(&$mform, $formdata, $configdata) {

        $this->add_common_section_settings(
            $mform,
            $formdata['sectionproperties'],
            $configdata['sectionproperties'],
            SEC_SEPARATOR,
            DEFAULT_COMMON_SECTION_PROPERTIES
        );

        $mform->addElement('header', 'moodle', "Separator ");
        // Separator style.
        $title = 'style';
        $options = array(
            'solid' => get_string('separatorsolid', COMPONENT),
            'double' => get_string('separatordouble', COMPONENT),
            'dashed' => get_string('separatordashed', COMPONENT),
            'dotted' => get_string('separatordotted', COMPONENT),
            'blur' => get_string('separatorblur', COMPONENT),
            'blurend' => get_string('separatorblurend', COMPONENT),
            'gradient' => get_string('separatorgradient', COMPONENT),
        );
        $defaultval = (!isset($formdata[$title])) ? 'solid' : $formdata[$title];
        $mform->addElement(
            'select',
            $title,
            get_string('separatorstyle', COMPONENT),
            $options,
            array('class' => 'ml-0 mr-5 mb-10 separator-style')
        );
        $mform->setDefault($title, $defaultval);

        // Separator color.
        $title = 'color';
        $defaultval = (!isset($formdata[$title])) ? '#000000' : $formdata[$title];
        $html = $this->get_color_picker($title, $defaultval, 'separator-style');
        $mform->addElement('html', $html);
        $mform->setType($title, PARAM_TEXT);
        $mform->setDefault($title, $defaultval);

        // Separator second color.
        $title = 'color2';
        $classname = 'separator-style ';
        if ((!isset($formdata['style'])) || $formdata['style'] != 'gradient') {
            $classname .= ' d-none';
        }
        $defaultval = (!isset($formdata[$title])) ? '#ffffff' : $formdata[$title];
        $html = $this->get_color_picker($title, $defaultval, $classname, get_string('colorhex', COMPONENT) . ' 2');
        $mform->addElement('html', $html);
        $mform->setType($title, PARAM_TEXT);
        $mform->setDefault($title, $defaultval);

        // Separator width.
        $title = 'width';
        $options = array_combine(range(5, 100, 5), range(5, 100, 5));
        $defaultval = (!isset($formdata[$title])) ? '100' : $formdata[$title];
        $mform->addElement(
            'select',
            $title,
            get_string('separator'.$title, COMPONENT),
            $options,
            array('class' => ' ml-0 mr-5 mb-10 separator-style')
        );
        $mform->setDefault($title, $defaultval);

        // Number of rows Setting.
        $title = 'height';
        $heights = [
            '0.5' => '0.5',
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
        ];
        $defaultval = (!isset($formdata[$title])) ? 1 : $formdata[$title];
        $mform->addElement(
            'select',
            $title,
            get_string('separatorheight', COMPONENT),
            $heights,
            array('class' => 'ml-0 mr-5 mb-10 separator-style')
        );
        $mform->setDefault($title, $defaultval);

        $mform->addElement('html', $this->separator_output($formdata));
    }


    /**
     * Save separator files and update configuration
     * @param  array $oldconfig Old configuration saved in database
     * @param  array $newconfig New configuration submitted in form
     * @return array            Updated configuration
     */
    public function update_separator_files($oldconfig, $newconfig) {
        // This call to delete the existing files.
        $this->update_separator_file_area($oldconfig, true);
        // This call to save the files.
        return $this->update_separator_file_area($newconfig, false);
    }

    /**
     * Update file uploaded in courses form.
     * @param  array   $configdata Configuration data
     * @param  boolean $delete     If true then files from configuration will be deleted
     * @return array               If not deleting then return updated configuration
     */
    public function update_separator_file_area($configdata, $delete = true) {
        global $CFG, $OUTPUT;

        $configdata = $this->update_section_bg_file(SEC_SEPARATOR, $configdata, $delete);

        if ($delete) {
            return;
        }

        // Need to save updated configdata, that is why returning here.
        return $configdata;
    }

    /**
     * Create copy of all files used in courses for draft config
     * @param  array $configdata draft config data array
     * @return array             updated draft config
     */
    private function separator_duplicate_file_in_config($configdata) {
        global $CFG, $OUTPUT;

        $configdata = $this->duplicate_section_bg_file(SEC_SEPARATOR, $configdata);

        return $this->update_separator_file_area($configdata, false);
    }

    /**
     * Create css styles based on form submission and assign css property
     * @param  int    $id       instance id
     * @param  array  $formdata data submitted in form
     * @return array            updated form data
     */
    private function separator_process_form_submission($id, $formdata) {
        $formdata['css'] = $this->separator_style($formdata);
        return $formdata;
    }
}
