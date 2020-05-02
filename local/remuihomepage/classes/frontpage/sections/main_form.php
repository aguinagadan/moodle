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

use html_writer;

define('THEME_COMPONENT', 'theme_remui');
define('COMPONENT', 'local_remuihomepage');
define('DEFAULT_COMMON_SECTION_PROPERTIES',
    array(
        'container' => true,
        'padding' => true,
        "bgfixed" => true,
        'itemid' => true,
        'bgopacity' => true,
        'bgcolor' => true,
        'shadowless' => true,
        'shadowcolor' => true,
        'customcss' => true,
    )
);

require_once($CFG->libdir .'/formslib.php');
require_once($CFG->dirroot . '/repository/lib.php');

class main_form extends \moodleform {

    use slider_form;
    use aboutus_form;
    use contact_form;
    use team_form;
    use courses_form;
    use testimonial_form;
    use feature_form;
    use html_form;
    use separator_form;

    /**
     * Form definition.
     * @return void
     */
    public function definition() {
        global $PAGE, $DB, $CFG;
        $mform = $this->_form;
        $formdata = null;

        if (isset($this->_customdata->dontinitialize) && $this->_customdata->dontinitialize == true) {
            return;
        }

        // Add instance id as hidden field.
        $mform->disable_form_change_checker();
        $mform->addElement('hidden', 'instanceid')->setValue($this->_customdata->instanceid);
        $mform->setType('instanceid', PARAM_INT);

        // Get the section details from the instance id.
        $sm = new \local_remuihomepage\frontpage\section_manager();
        $record = $sm->get_config_by_instanceid($this->_customdata->instanceid);
        $formdata = json_decode($record->draftconfig, true);

        // Check if formdata is being passed with instance id.
        if (isset($this->_customdata->formdata)) {
            $serialiseddata = json_decode($this->_customdata->formdata);
            if (isset($serialiseddata['qf'])) {
                unset($serialiseddata['qf']);
            }
            $formdata = array();
            parse_str($serialiseddata, $formdata);
            $formdata = $sm->convert_to_array($formdata);
            $formdata['fromform'] = true;
        }

        // Add setting defined in section form.
        if ($record != null) {
            $sectionname = trim($record->name).'form';
            if (method_exists($this, $sectionname)) {
                $this->$sectionname($mform, $formdata, json_decode($record->draftconfig, true));
            }
        }
    }


    /**
     * Add common section settings like background image, background color and padding
     * @param moodleform &$mform       moodleform object
     * @param array      $formdata     form data to be loaded into properties
     * @param array      $dbconfigdata db form data
     * @param string     $filearea     filearea for background image
     * @param array      $settings     Section properties
     * @param array      $reload       Settings should be reloaded or loaded from new
     */
    public function add_common_section_settings(
        &$mform,
        $formdata,
        $dbconfigdata,
        $filearea,
        $settings = DEFAULT_COMMON_SECTION_PROPERTIES,
        $reload = false
    ) {
        $mform->addElement('header', 'moodle', get_string('sectionsetting', COMPONENT));

        // Container Margin Width.
        $title = 'container';
        if (isset($settings[$title]) && $settings[$title]) {
            $options = array(0 => get_string('fullwidth', COMPONENT), 1 => get_string($title, COMPONENT) );
            $defaultval = (!isset($formdata[$title])) ? 0 : $formdata[$title];
            $mform->addElement(
                'select',
                'sectionproperties_' . $title,
                get_string('container', COMPONENT), $options, array('class' => ' ml-0 mr-5 mb-10')
            );
            $mform->setDefault('sectionproperties_'.$title, $defaultval);

            $options = array(
                '0' => '0',
                '3' => '3',
                '5' => '5',
                '10' => '10',
                '15' => '15',
                '20' => '20',
                '25' => '25',
                '30' => '30',
                '35' => '35',
                '40' => '40',
                '45' => '45',
                '50' => '50',
                '60' => '60',
                '70' => '70',
                '80' => '80',
                '100' => '100',
            );
        }

        // Padding Settings Group.
        if (isset($settings['padding']) && $settings['padding']) {
            $buttonarray = array();
            $title = 'ptop';
            $defaultval = (!isset($dbformdata[$title])) ? $options['10'] : $dbconfigdata[$title];
            $defaultval = (!isset($formdata[$title])) ? $defaultval : $formdata[$title];
            $element = $mform->createElement('select', 'sectionproperties_'.$title, 'top', $options);
            $element->setValue($defaultval);
            $buttonarray[] = $element;

            $title = 'pright';
            $defaultval = (!isset($dbformdata[$title])) ? $options['10'] : $dbconfigdata[$title];
            $defaultval = (!isset($formdata[$title])) ? $defaultval : $formdata[$title];
            $element = $mform->createElement('select', 'sectionproperties_'.$title, 'right', $options);
            $element->setValue($defaultval);
            $buttonarray[] = $element;

            $title = 'pbottom';
            $defaultval = (!isset($dbformdata[$title])) ? $options['10'] : $dbconfigdata[$title];
            $defaultval = (!isset($formdata[$title])) ? $defaultval : $formdata[$title];
            $element = $mform->createElement('select', 'sectionproperties_'.$title, 'bottom', $options);
            $element->setValue($defaultval);
            $buttonarray[] = $element;

            $title = 'pleft';
            $defaultval = (!isset($dbformdata[$title])) ? $options['10'] : $dbconfigdata[$title];
            $defaultval = (!isset($formdata[$title])) ? $defaultval : $formdata[$title];
            $element = $mform->createElement('select', 'sectionproperties_'.$title, 'left', $options);
            $element->setValue($defaultval);
            $buttonarray[] = $element;

            $buttonarray[] = $mform->createElement('html', '[ Top-Right-Bottom-Left ]');
            $mform->addGroup($buttonarray, 'sectionpadding', get_string('sectionpadding', COMPONENT), '', false);

            $title = "bgfixed";
            $options = array(0 => get_string("nobgfixed", COMPONENT), 1 => get_string('bgfixed', COMPONENT));
            $defaultval = (!isset($formdata[$title])) ? 0 : $formdata[$title];
            $mform->addElement(
                'select',
                'sectionproperties_' . $title,
                get_string('bgfixed', COMPONENT), $options, array('class' => ' ml-0 mr-5 mb-10')
            );
            $mform->setDefault('sectionproperties_'.$title, $defaultval);
        }

        // Background Image.
        $title = 'itemid';
        if (isset($settings[$title]) && $settings[$title]) {
            $draftitemid = null;

            if ($reload) {
                $draftitemid = $formdata['itemid'];
            } else if (isset($formdata['itemid']) && $formdata['itemid'] != '') {
                $context = \context_system::instance();
                $itemid = $formdata['itemid'];
                // Load the file from database to draft area.
                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    THEME_COMPONENT,
                    $filearea,
                    $itemid,
                    array(
                        'subdirs' => 0,
                        'maxfiles' => 1
                    )
                );
            }

            $mform->addElement(
                'filemanager',
                'sectionproperties_'.$title,
                get_string('sectionbackground', COMPONENT),
                array('class' => ' ml-0 mr-5 mb-10' ),
                array(
                    'subdirs' => 0,
                    'maxbytes' => 2048,
                    'areamaxbytes' => 10485760,
                    'maxfiles' => 1,
                    'accepted_types' => 'web_image'
                )
            )->setValue($draftitemid);
        }

        $title = 'bgopacity';
        if (isset($settings[$title]) && $settings[$title]) {
            $options = [
                '0' => get_string('none', COMPONENT),
                '0.1' => '0.1',
                '0.2' => '0.2',
                '0.3' => '0.3',
                '0.4' => '0.4',
                '0.5' => '0.5',
                '0.6' => '0.6',
                '0.7' => '0.7',
                '0.8' => '0.8',
                '0.9' => '0.9',
                '1' => '1.0'
            ];
            $defaultval = (!isset($formdata[$title])) ? '0' : $formdata[$title];
            $mform->addElement(
                'select',
                'sectionproperties_' . $title,
                get_string($title, COMPONENT), $options, array('class' => ' ml-0 mr-5 mb-10')
            );
            $mform->setDefault('sectionproperties_'.$title, $defaultval);
        }

        $title = 'bgcolor';
        if (isset($settings[$title]) && $settings[$title]) {
            $defaultval = (!isset($formdata[$title])) ? '#ffffff' : $formdata[$title];
            $html = $this->get_color_picker('sectionproperties_'.$title, $defaultval, '', get_string($title, COMPONENT));
            $mform->addElement('html', $html);
            $mform->setType($title, PARAM_TEXT);
        }

        $title = 'shadowless';
        if (isset($settings[$title]) && $settings[$title]) {
            $defaultval = (!isset($formdata[$title])) ? 0 : $formdata[$title];
            $mform->addElement(
                'checkbox',
                'sectionproperties_' . $title,
                get_string($title, COMPONENT), get_string($title.'desc', COMPONENT),  array('class' => 'ml-0 mr-5 mb-10')
            );
            $mform->setDefault('sectionproperties_'.$title, $defaultval);
        }

        $title = 'shadowcolor';
        if (isset($settings[$title]) && $settings[$title]) {
            $defaultval = (!isset($formdata[$title])) ? '#cccccc' : $formdata[$title];
            $html = $this->get_color_picker('sectionproperties_'.$title, $defaultval, '', get_string($title, COMPONENT));
            $mform->addElement('html', $html);
            $mform->setType($title, PARAM_TEXT);
        }

        $title = 'customcss';
        if (isset($settings[$title]) && $settings[$title]) {
            $defaultval = (!isset($formdata[$title])) ? '' : $formdata[$title];
            $mform->addElement('textarea', 'sectionproperties_'.$title, get_string($title, COMPONENT), array(
                'class' => 'ml-0 mr-5 mb-10',
                'placeholder' => get_string('sectioncustomcssdesc', COMPONENT),
                'wrap' => 'virtual',
                'rows' => 10
            ));
            $mform->setDefault('sectionproperties_'.$title, $defaultval);
        }
    }

    /**
     * Add text properties to title settings
     * @param moodleform &$mform      moodleform Object
     * @param array      $formdata    form data to be loaded into properties
     * @param string     $titleprefix target element name
     */
    public function add_section_title_settings(&$mform, $formdata, $textobj, $titleprefix = '') {

        // Title Description Input field.

        if (!isset($textobj['noinput']) || !$textobj['noinput']) {
            $title = 'text';
            $defaultval = (!isset($formdata[$title])) ? "" : $formdata[$title];

            $options = array();

            if ($textobj['type'] == 'textarea') {
                $options = array('autosave' => false);
            }
            $mform->addElement($textobj['type'], $titleprefix.'_'.$title, $textobj['label'],  array(
                'class' => 'ml-0 mr-5 mb-10',
                'placeholder' => $textobj['placeholder']
            ), $options);

            $mform->setType($titleprefix.'_'.$title, PARAM_TEXT);
            $mform->setDefault($titleprefix.'_'.$title, $defaultval);

            if (isset($textobj['required']) && $textobj['required']) {
                $mform->addRule($titleprefix.'_'.$title, $textobj['requiredmsg'], 'required', null, 'client');
            }
        }

        if (isset($textobj['noattrib']) && $textobj['noattrib'] == true) {
            return;
        }

        // Padding Settings Group.
        $buttonarray = array();

        if (!isset($textobj['onlycolor'])) {
            $title = 'textbold';
            $defaultval = (!isset($formdata[$title])) ? 0 : $formdata[$title];
            $element = $mform->createElement('checkbox', $titleprefix ."_".$title, get_string('textbold', COMPONENT));
            $mform->setDefault($titleprefix ."_".$title, $defaultval);
            $buttonarray[] = $element;

            $title = 'textitalic';
            $defaultval = (!isset($formdata[$title])) ? 0 : $formdata[$title];
            $element = $mform->createElement('checkbox', $titleprefix ."_".$title, get_string('textitalic', COMPONENT));
            $mform->setDefault($titleprefix ."_".$title, $defaultval);
            $buttonarray[] = $element;

            $title = 'textunderline';
            $defaultval = (!isset($formdata[$title])) ? 0 : $formdata[$title];
            $element = $mform->createElement('checkbox', $titleprefix ."_".$title, get_string('textunderline', COMPONENT));
            $mform->setDefault($titleprefix ."_".$title, $defaultval);
            $buttonarray[] = $element;

            $title = 'fontsize';
            $options = array(
                '0'  => '0px',
                '10' => '10px',
                '12' => '12px',
                '14' => '14px',
                '16' => '16px',
                '18' => '18px',
                '20' => '20px',
                '24' => '24px',
                '26' => '26px',
                '30' => '30px',
                '40' => '40px',
                '50' => '50px',
                '60' => '60px',
                '70' => '70px',
                '80' => '80px'
            );
            $defaultval = (!isset($formdata[$title])) ? $options['10'] : $formdata[$title];
            $element = $mform->createElement('select', $titleprefix ."_".$title, 'font', $options);
            $element->setValue($defaultval);
            $buttonarray[] = $element;

            $buttonarray[] = $mform->createElement('html', get_string('fontsize', COMPONENT));
        }

        $title = 'color';
        $defaultval = (!isset($formdata[$title])) ? '#000000' : $formdata[$title];
        $html = html_writer::tag('input', '', array(
            'type' => 'color',
            'name' => $titleprefix . "_" . $title,
            'value' => $defaultval,
            'class' => 'form-control col-3 custom-file-picker'
        ));
        $element = $mform->createElement('html', $html);
        $buttonarray[] = $element;

        $buttonarray[] = $mform->createElement('html', get_string('color', COMPONENT));
        if (isset($textobj['noinput']) && $textobj['noinput']) {
            $mform->addGroup($buttonarray, 'titleeditor', $textobj['label'], '', false);
            $mform->addElement('html', $this->get_description($textobj['placeholder']));
        } else {
            $mform->addGroup($buttonarray, 'titleeditor', "", '', false);
        }
    }

    /**
     * Get description container to add just text in mform
     * @param  string $text Text to show in description container
     * @return string       Description container html
     */
    public function get_description($text) {
        $html  = '<div class="form-group row  fitem ml-0 mr-5 mb-10">';
        $html .= '<div class="col-md-3">';
        $html .= '<span class="pull-xs-right text-nowrap"></span>';
        $html .= '</div><div class="col-md-9 form-inline felement" data-fieldtype="text">';
        $html .= '<label>' . $text . '</label>';
        $html .= '</div></div>';
        return $html;
    }

    /**
     * Get color picker html
     * @param  string $title      Input name
     * @param  string $defaultval Default color value
     * @param  string $extraclass Extra classes for fitem
     * @param  string $label      Label for input
     * @return string             Color picker html
     */
    public function get_color_picker($title, $defaultval, $extraclass = '', $label = '', $desc = '') {
        $html = html_writer::start_tag('div', array('class' => "form-group row  fitem ml-0 mr-5 mb-10 " . $extraclass));
        $html .= html_writer::start_tag('div', array('class' => "col-md-3"));
        $html .= html_writer::start_tag('span', array('class' => "pull-xs-right text-nowrap"));
        $html .= html_writer::end_tag('span');
        $html .= html_writer::start_tag('label', array('class' => "col-form-label d-inline"));
        $html .= ($label == '') ? get_string('colorhex', COMPONENT) : $label;
        $html .= html_writer::end_tag('label');
        $html .= html_writer::end_tag('div');
        $html .= html_writer::start_tag('div', array('class' => "col-md-9 form-inline felement", 'data-fieldtype' => 'text'));
        $html .= html_writer::tag('input', '', array(
            'type' => 'color',
            'name' => $title,
            'value' => $defaultval,
            'class' => 'form-control col-3 custom-file-picker mb-10'
        ));
        $html .= html_writer::start_tag('label', array('class' => 'colorhex-description'));
        $html .= ($desc == '') ? get_string('colorhexdesc', COMPONENT) : $desc;
        $html .= html_writer::end_tag('label');
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
        return $html;
    }

    /**
     * This function will update new or delete the section background file.
     * Each block will have this file
     * @param  string  $filearea   section file area
     * @param  array   $configdata configuration data of section
     * @param  boolean $delete     if true then background file in the configuration will be deleted
     * @return array               configuration data
     */
    public function update_section_bg_file($filearea, $configdata, $delete = true) {
        global $CFG;
        require_once($CFG->dirroot . "/theme/remui/lib.php");

        $fs = get_file_storage();
        $systemcontext = \context_system::instance();

        if (isset($configdata['sectionproperties']['itemid']) && $configdata['sectionproperties']['itemid'] != '') {
            $itemid = $configdata['sectionproperties']['itemid'];
            if ($delete) {
                $fs->delete_area_files($systemcontext->id, THEME_COMPONENT, $filearea, $itemid);
            } else {
                $newitemid = theme_remui_get_unused_itemid($filearea);
                file_save_draft_area_files($itemid, $systemcontext->id, THEME_COMPONENT, $filearea, $newitemid);
                $configdata['sectionproperties']['imageurl'] = get_file_img_url($newitemid, THEME_COMPONENT, $filearea);
                $configdata['sectionproperties']['itemid'] = $configdata['sectionproperties']['imageurl'] != '' ? $newitemid : 0;
                $this->delete_draft_file($itemid);
            }
        }

        if ($delete) {
            return;
        }

        // Need to save updated configdata, that is why returning here.
        return $configdata;
    }

    /**
     * Duplicate background file and replace it's itemid with draft itemid
     * @param  string $filearea   file area name
     * @param  array  $configdata configration data
     * @return array              updated configuration data
     */
    private function duplicate_section_bg_file($filearea, $configdata) {
        $context = \context_system::instance();
        if (isset($configdata['sectionproperties']['itemid']) && $configdata['sectionproperties']['itemid'] != '') {
            $draftitemid = null;
            $itemid = $configdata['sectionproperties']['itemid'];
            file_prepare_draft_area(
                $draftitemid,
                $context->id,
                THEME_COMPONENT,
                $filearea,
                $itemid,
                array(
                    'subdirs' => 0,
                    'maxfiles' => 1
                )
            );
            $configdata['sectionproperties']['itemid'] = $draftitemid;
        }
        return $configdata;
    }

    /**
     * Process section data before creating section
     */
    public function process_section_creation() {
        global $DB;
        $id = $this->_customdata->instanceid;
        $formdata = $this->_customdata->formdata;
        $record = $DB->get_record(SECTIONTABLE, array('id' => $id));

        $processsectioncreation = $record->name."_process_section_creation";
        if (method_exists($this, $processsectioncreation)) {
            $formdata = $this->$processsectioncreation($id, $formdata);
        }
        $this->_customdata->formdata = $formdata;
    }

    /**
     * Process form data on submission
     * @param  bool   $processfiles true if files need to be processed
     * @return object               formdata
     */
    public function process_form_submission($processfiles = true) {
        global $DB;
        $id = $this->_customdata->instanceid;
        $formdata = $this->_customdata->formdata;
        $record = $DB->get_record(SECTIONTABLE, array('id' => $id));
        // Process files.
        $filemethod = "update_{$record->name}_files";
        if ($processfiles && method_exists($this, $filemethod)) {
            $formdata = $this->$filemethod(json_decode($record->draftconfig, true), $formdata);
        }
        $processformsubmission = $record->name."_process_form_submission";

        if (method_exists($this, $processformsubmission)) {
            $formdata = $this->$processformsubmission($id, $formdata);
        }

        // Manipulate section properties and generate html output to save mustache time.
        if (isset($formdata['sectionproperties'])) {
            $out = '';
            $properties = $formdata['sectionproperties'];
            $out = 'class="panel invisible section-' . $record->name . ' m-0 px-sm-0 ';
            $out .= isset($properties['imageurl']) && $properties['imageurl'] != '' ? 'bg-image ' : ' ';
            $out .= isset($properties['bgfixed']) && $properties['bgfixed'] == 1 ? 'bg-fixed ' : ' ';
            $out .= '"';
            $out .= ' style="';
            $opacity = "";
            if (isset($properties['imageurl']) && $properties['imageurl'] != '') {
                if (isset($properties['bgopacity']) && $properties['bgopacity'] != '0') {
                    $opacity = "linear-gradient(rgba(0, 0, 0, " . $properties['bgopacity'] . "),
                                                rgba(0, 0, 0, " . $properties['bgopacity'] . ")), ";
                }
                $out .= "background-image: ".$opacity."url({$properties['imageurl']});";
            } else if (isset($properties['bgcolor']) && $properties['bgcolor'] != '') {
                if (isset($properties['bgopacity']) && $properties['bgopacity'] != '0') {
                    $opacity = dechex($properties['bgopacity'] * 255);
                }
                $out .= "background-image: linear-gradient({$properties['bgcolor']}{$opacity}, {$properties['bgcolor']}{$opacity})";
            }

            $out .= '"';
            $formdata['sectionpropertiesoutput'] = $out;

            $out = isset($properties['ptop']) && $properties['ptop'] ? ' pt-' . $properties['ptop'] : ' ';
            $out .= isset($properties['pright']) && $properties['pright'] ? ' pr-lg-' . $properties['pright'] : ' ';
            $out .= isset($properties['pbottom']) && $properties['pbottom'] ? ' pb-' . $properties['pbottom'] : ' ';
            $out .= isset($properties['pleft']) && $properties['pleft'] ? ' pl-lg-' . $properties['pleft'] : ' ';
            $out .= isset($properties['container']) && $properties['container'] == 1 ? ' container' : ' w-full';
            $formdata['sectioncontaineroutput'] = $out;

            if (isset($properties['shadowless']) && $properties['shadowless']) {
                $formdata['shadowless'] = 1;
            }
        } else {
            $out = 'class="panel invisible section-' . $record->name . ' m-0 px-sm-0 "';
            $formdata['sectionpropertiesoutput'] = $out;
        }

        return $formdata;
    }

    /**
     * Get file mimetype by itemid and filearea
     * @param  object $fs        fs object
     * @param  object $context   context object
     * @param  int    $itemid    file itemid
     * @param  string $filearea  file area
     * @return string            File mime ttype
     */
    public function get_file_type($fs, $context, $itemid, $filearea) {

        $files = $fs->get_area_files($context->id, THEME_COMPONENT, $filearea, $itemid);
        foreach ($files as $key => $file) {
            if ($file->get_filename() !== ".") {
                return $file->get_mimetype();
            }
        }
        return "";
    }

    /**
     * Check if file exist at component and file area
     * @param  object      $fs        fs object
     * @param  object      $context   context object
     * @param  int         $itemid    file item id
     * @param  string      $component file component
     * @param  string      $area      file area
     * @return object|null            file object if file exist
     */
    public function is_file_exist_in_area($fs, $context, $itemid, $component, $area) {
        $files = $fs->get_area_files($context->id, $component, $area, $itemid);
        foreach ($files as $key => $file) {
            if ($file->get_filename() !== ".") {
                return $file;
            }
        }
        return null;
    }

    /**
     * Create draft config if draftconfig column is null
     * @param string $sectiontable Section table name
     */
    public function create_draft_configs($sectiontable) {
        global $DB;
        $id = $this->_customdata->instanceid;
        $section = $this->_customdata->section;
        if ($section->draftconfig == null) {
            $configdata = json_decode($section->configdata, true);
            $methodname = $section->name."_duplicate_file_in_config";
            // Call duplicate_file_in_config method from section form.
            if (method_exists($this, $methodname)) {
                $configdata = $this->$methodname($configdata);
            }
            if ($configdata) {
                $this->_customdata->formdata = $configdata;
                $configdata = $this->process_form_submission(false);
                $section->draftconfig = json_encode($configdata);
            }
            $DB->update_record($sectiontable, $section);
        }
    }

    /**
     * Publish configuration from draft to all users
     * @param string $sectiontable Section table name
     */
    public function publish_draft_configs($sectiontable) {
        global $DB;
        $id = $this->_customdata->instanceid;
        $section = $this->_customdata->section;
        $configdata = $section->draftconfig;
        if ($configdata) {
            $methodname = "update_{$section->name}_file_area";
            if (method_exists($this, $methodname) && $section->configdata != null) {
                $this->$methodname(json_decode($section->configdata, true));
            }
            $section->configdata = $configdata;
            $section->draftconfig = null;
        }
        $DB->update_record($sectiontable, $section);
    }

    /**
     * Delete file from draft area using itemids
     * @param  int|array $itemids draftitemids
     */
    public function delete_draft_file($itemids) {
        global $USER;
        if (!is_array($itemids)) {
            $itemids = [$itemids];
        }
        foreach ($itemids as $key => $itemid) {
            $context = \context_user::instance($USER->id);
            $fs = get_file_storage();
            $fs->delete_area_files($context->id, 'user', 'draft', $itemid);
        }
    }
}
