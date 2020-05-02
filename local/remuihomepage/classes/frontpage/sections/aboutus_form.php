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
define('SEC_ABOUTUS', 'section_aboutus');
use local_remuihomepage\frontpage\section_manager as section_manager;
use context_system;

trait aboutus_form {

    /**
     * Returns the About us form.
     * @param  stdClass &$mform   Form object.
     * @param  array    $formdata Form data.
     * @param  string   $config   Saved configuration
     * @return stdClass           Form object with data.
     */
    private function aboutusform(&$mform, $formdata, $configdata) {

        $secman = new section_manager();

        $this->add_common_section_settings(
            $mform,
            $formdata['sectionproperties'],
            $configdata['sectionproperties'],
            SEC_ABOUTUS,
            DEFAULT_COMMON_SECTION_PROPERTIES
        );

        $mform->addElement('header', 'moodle', get_string('aboutus', COMPONENT));

        $title = 'title';
        $textobj = array(
                'label' => get_string($title, COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('titleplaceholder', COMPONENT),
                'required' => false
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $title = 'description';
        $textobj = array(
                'label' => get_string($title, COMPONENT),
                'type' => 'textarea',
                'placeholder' => get_string('descriptionplaceholder', COMPONENT),
                'required' => false
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $title = 'btnlink';
        $defaultval = (!isset($formdata[$title])) ? "" : $formdata[$title];
        $mform->addElement('text', $title, get_string($title, COMPONENT),  array(
            'class' => 'ml-0 mr-5 mb-10',
            'placeholder' => get_string('btnlinkplaceholder', COMPONENT)
        ));
        $mform->setType($title, PARAM_TEXT);
        $mform->setDefault($title, $defaultval);

        $title = 'btnlabel';
        $defaultval = (!isset($formdata[$title])) ? "" : $formdata[$title];
        $mform->addElement('text', $title, get_string($title, COMPONENT),  array(
            'class' => 'ml-0 mr-5 mb-10',
            'placeholder' => get_string('btnlabelplaceholder', COMPONENT)
        ));
        $mform->setType($title, PARAM_TEXT);
        $mform->setDefault($title, $defaultval);

        $options = array(
            '0' => '0px',
            '1' => '1px',
            '2' => '2px',
            '3' => '3px',
            '4' => '4px',
            '5' => '5px',
            '6' => '6px',
            '7' => '7px',
            '8' => '8px',
            '9' => '9px',
            '10' => '10px'
        );

        $title = 'cardradius';
        $defaultval = (!isset($formdata[$title])) ? "0" : $formdata[$title];
        $mform->addElement('select', $title, get_string($title, COMPONENT), $options, array('class' => ' ml-0 mr-5 mb-10'));
        $mform->setType($title, PARAM_TEXT);
        $mform->setDefault($title, $defaultval);

        // Background Image.
        $title = 'image';

        $draftitemid = null;

        if (isset($formdata['image'])) {
            $context = \context_system::instance();
            $itemid = $formdata['image'];
            // Load the file from database to draft area.
            file_prepare_draft_area(
                $draftitemid,
                $context->id,
                THEME_COMPONENT,
                SEC_ABOUTUS,
                $itemid,
                array(
                    'subdirs' => 0,
                    'maxfiles' => 1
                )
            );
        }

        $mform->addElement(
            'filemanager',
            $title,
            get_string('image', COMPONENT),
            array('class' => ' ml-0 mr-5 mb-10' ),
            array(
                'subdirs' => 0,
                'maxbytes' => 2048,
                'areamaxbytes' => 10485760,
                'maxfiles' => 50,
                'accepted_types' => 'web_image',
                'return_types' => FILE_INTERNAL | FILE_EXTERNAL
            )
        )->setValue($draftitemid);

        $title = 'view';
        $values = array(0 => get_string('rowview', COMPONENT), 1 => get_string('gridview', COMPONENT));
        $defaultval = (!isset($formdata[$title])) ? "" : $formdata[$title];
        $mform->addElement('select', $title, get_string('view', COMPONENT), $values, array('class' => ' ml-0 mr-5 mb-10'));
        $mform->setType($title, PARAM_TEXT);
        $mform->setDefault($title, $defaultval);

        for ($blrow = 0; $blrow < 4; $blrow++) {
            $mform->addElement('header', 'moodle', get_string('block', COMPONENT)." ".($blrow + 1));

            $title = 'block_'.$blrow.'_title';
            $textobj = array(
                'label' => get_string('title', COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('titleplaceholder', COMPONENT),
                'required' => false,
                'onlycolor' => true
            );
            $this->add_section_title_settings($mform, $formdata['block'][$blrow]['title'], $textobj, $title);

            $title = 'block_'.$blrow.'_description';
            $textobj = array(
                'label' => get_string('description', COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('descriptionplaceholder', COMPONENT),
                'required' => false,
                'onlycolor' => true
            );
            $this->add_section_title_settings($mform, $formdata['block'][$blrow]['description'], $textobj, $title);

            $title = 'block_'.$blrow.'_icon';
            $defaultval = (!isset($formdata['block'][$blrow]['icon'])) ? "" : $formdata['block'][$blrow]['icon'];
            $mform->addElement('text', $title, get_string('icon', COMPONENT), array('class' => ' ml-0 mr-5 mb-10' ));
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);

            $title = 'block_'.$blrow.'_color';
            $defaultval = (!isset($formdata['block'][$blrow]['color'])) ? "" : $formdata['block'][$blrow]['color'];
            $html = $this->get_color_picker($title, $defaultval);
            $mform->addElement('html', $html);
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);

            $title = 'block_'.$blrow.'_btnlink';
            $defaultval = (!isset($formdata['block'][$blrow]['btnlink'])) ? "" : $formdata['block'][$blrow]['btnlink'];
            $mform->addElement('text', $title, get_string('link', COMPONENT), array(
                'class' => 'ml-0 mr-5 mb-10',
                'placeholder' => get_string('btnlinkplaceholder', COMPONENT)
            ));
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);

            $title = 'block_'.$blrow.'_btnlabel';
            $textobj = array(
                'label' => get_string('linklabel', COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('btnlabelplaceholder', COMPONENT),
                'required' => false,
                'onlycolor' => true
            );
            $this->add_section_title_settings($mform, $formdata['block'][$blrow]['btnlabel'], $textobj, $title);

            $options = [0 => get_string('noborder', COMPONENT), 1 => get_string('border', COMPONENT)];
            $title = 'block_'.$blrow.'_border';
            $defaultval = (!isset($formdata['block'][$blrow]['border'])) ? 0 : $formdata['block'][$blrow]['border'];
            $mform->addElement('select', $title, get_string('border', COMPONENT), $options, array('class' => ' ml-0 mr-5 mb-10' ));
            $mform->setDefault($title, $defaultval);

            $options = [0 => get_string('transparent', COMPONENT), 1 => get_string('image', COMPONENT)];
            $title = 'block_'.$blrow.'_blockbackground';
            $defaultval = (!isset($formdata['block'][$blrow]['blockbackground'])) ?
                           0 : $formdata['block'][$blrow]['blockbackground'];
            $mform->addElement(
                'select',
                $title,
                get_string('blockbackground', COMPONENT),
                $options,
                array('class' => ' ml-0 mr-5 mb-10')
            );
            $mform->setDefault($title, $defaultval);

            // Background Image.
            $title = 'block_'.$blrow.'_image';

            $draftitemid = null;

            if (isset($formdata['block'][$blrow]['image'])) {
                $context = \context_system::instance();
                $itemid = $formdata['block'][$blrow]['image'];
                // Load the file from database to draft area.
                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    THEME_COMPONENT,
                    SEC_ABOUTUS,
                    $itemid,
                    array(
                        'subdirs' => 0,
                        'maxfiles' => 1
                    )
                );
            }

            $mform->addElement(
                'filemanager',
                $title,
                get_string('image', COMPONENT),
                array('class' => ' ml-0 mr-5 mb-10' ),
                array(
                    'subdirs' => 0,
                    'maxbytes' => 2048,
                    'areamaxbytes' => 10485760,
                    'maxfiles' => 50,
                    'accepted_types' => 'web_image'
                )
            )->setValue($draftitemid);
        }
    }


    /**
     * Save aboutus files and update configuration
     * @param  array $oldconfig Old configuration saved in database
     * @param  array $newconfig New configuration submitted in form
     * @return array            Updated configuration
     */
    public function update_aboutus_files($oldconfig, $newconfig) {
        // This call to delete the existing files.
        $this->update_aboutus_file_area($oldconfig, true);
        // This call to save the files.
        return $this->update_aboutus_file_area($newconfig, false);
    }

    /**
     * Update file uploaded in aboutus form.
     * @param  array   $configdata Configuration data
     * @param  boolean $delete     If true then files from configuration will be deleted
     * @return array               If not deleting then return updated configuration
     */
    public function update_aboutus_file_area($configdata, $delete = true) {
        global $CFG, $OUTPUT;
        require_once($CFG->dirroot . "/theme/remui/lib.php");

        $systemcontext = context_system::instance();
        $fs = get_file_storage();

        // In this section we have extra image for background, this if block handle this bg image.
        if (isset($configdata['image'])) {
            $itemid = $configdata['image'];
            if ($itemid != "" || $itemid != null) {
                if ($delete) {
                    $fs->delete_area_files($systemcontext->id, THEME_COMPONENT, SEC_ABOUTUS, $itemid);
                } else {
                    $newitemid = theme_remui_get_unused_itemid(SEC_ABOUTUS);
                    file_save_draft_area_files($itemid, $systemcontext->id, THEME_COMPONENT, SEC_ABOUTUS, $newitemid);
                    $configdata['imageurl'] = get_file_img_url($newitemid, THEME_COMPONENT, SEC_ABOUTUS);
                    $configdata['image'] = $configdata['imageurl'] != '' ? $newitemid : 0;
                    $this->delete_draft_file($itemid);
                }
            }
        }

        // This for loop is for the 4 sections in about us.
        for ($blrow = 0; $blrow < 4; $blrow++) {
            $itemid = isset($configdata['block'][$blrow]['image']) ? $configdata['block'][$blrow]['image'] : null;
            if ($itemid != "" || $itemid != null) {
                if ($delete) {
                    $fs->delete_area_files($systemcontext->id, THEME_COMPONENT, SEC_ABOUTUS, $itemid);
                    continue;
                }
                $newitemid = theme_remui_get_unused_itemid(SEC_ABOUTUS);
                file_save_draft_area_files($itemid, $systemcontext->id, THEME_COMPONENT, SEC_ABOUTUS, $newitemid);
                $configdata['block'][$blrow]['imageurl'] = get_file_img_url($newitemid, THEME_COMPONENT, SEC_ABOUTUS);
                $configdata['block'][$blrow]['image'] = $configdata['block'][$blrow]['imageurl'] != '' ? $newitemid : 0;

                if ($configdata['block'][$blrow]['blockbackground'] == '0') {
                    unset($configdata['block'][$blrow]['blockbackground']);
                }
                if ($configdata['block'][$blrow]['border'] == '0') {
                    unset($configdata['block'][$blrow]['border']);
                }
            }
        }

        $configdata = $this->update_section_bg_file(SEC_ABOUTUS, $configdata, $delete);

        if ($delete) {
            return;
        }
        // Need to save updated configdata, that is why returning here.
        return $configdata;
    }

    /**
     * Create copy of all files used in aboutus for draft config
     * @param  array $configdata draft config data array
     * @return array             updated draft config
     */
    private function aboutus_duplicate_file_in_config($configdata) {
        global $CFG, $OUTPUT;
        require_once($CFG->dirroot . "/theme/remui/lib.php");

        $context = context_system::instance();
        $fs = get_file_storage();

        // In this section we have extra image for background, this if block handle this bg image.
        if (isset($configdata['image'])) {
            $itemid = $configdata['image'];
            if ($itemid != "" || $itemid != null) {
                $draftitemid = null;
                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    THEME_COMPONENT,
                    SEC_ABOUTUS,
                    $itemid,
                    array(
                        'subdirs' => 0,
                        'maxfiles' => 1
                    )
                );
                $configdata['image'] = $draftitemid;
            }
        }

        // This for loop is for the 4 sections in about us.
        for ($blrow = 0; $blrow < 4; $blrow++) {
            $itemid = isset($configdata['block'][$blrow]['image']) ? $configdata['block'][$blrow]['image'] : null;
            if ($itemid != "" || $itemid != null) {
                $draftitemid = null;
                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    THEME_COMPONENT,
                    SEC_ABOUTUS,
                    $itemid,
                    array(
                        'subdirs' => 0,
                        'maxfiles' => 1
                    )
                );
                $configdata['block'][$blrow]['image'] = $draftitemid;
            }
        }

        $configdata = $this->duplicate_section_bg_file(SEC_ABOUTUS, $configdata);

        return $this->update_aboutus_file_area($configdata, false);
    }

    private function aboutus_process_form_submission($id, $configdata) {
        $configdata['hastitledescription'] = !empty($configdata['title']['text']) || !empty($configdata['description']['text']);
        if (isset($configdata['view']) && !$configdata['view']) {
            // View Change Row Or Grid.
            // Changing the view by removing view attribute, because mustache understands only exist or not type of if--else.
            unset($configdata['view']);
        }

        return $configdata;
    }
}
