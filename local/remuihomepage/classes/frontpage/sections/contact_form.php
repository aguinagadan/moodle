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
define('SEC_CONTACT', 'section_contact');
trait contact_form {


    /**
     * Returns the Contact form.
     * @param  stdClass &$mform   Form object.
     * @param  array    $formdata Form data.
     * @return stdClass   Form object with data.
     */
    private function contactform(&$mform, $formdata, $configdata) {

        $this->add_common_section_settings(
            $mform,
            $formdata['sectionproperties'],
            $configdata['sectionproperties'],
            SEC_CONTACT,
            DEFAULT_COMMON_SECTION_PROPERTIES
        );

        $mform->addElement('header', 'moodle', "Contact ");
        // Title.
        $title = 'title';
        $textobj = array(
                'label' => get_string($title, COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('titleplaceholder', COMPONENT),
                'required' => false
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $title = 'phone';
        $textobj = array(
                'label' => get_string($title, COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('contactlabelplaceholder', COMPONENT),
                'required' => false,
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $title = 'email';
        $textobj = array(
                'label' => get_string($title, COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('contactplaceholder', COMPONENT),
                'required' => false
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $title = 'description';
        $textobj = array(
                'label' => get_string($title, COMPONENT),
                'type' => 'textarea',
                'placeholder' => get_string('contactplaceholder', COMPONENT),
                'required' => false,
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $mform->addElement('header', 'moodle', "Social ");

        $title = 'socialview';
        $values = ['square' => 'Square', 'round' => 'Round'];
        $defaultval = (!isset($formdata['socialview'])) ? $values['square'] : $formdata['socialview'];
        $mform->addElement('select', $title, get_string('socialview', COMPONENT), $values, array('class' => ' ml-0 mr-5 mb-10' ));
        $mform->setDefault($title, $defaultval);

        $socials = ['quora', 'facebook', 'youtube', 'twitter', 'google', 'linkedin', 'pinterest', 'instagram'];

        foreach ($socials as $key => $social) {
            $title = $social;
            $defaultval = (!isset($formdata[$title])) ? "" : $formdata[$title];
            $mform->addElement('text', $title, get_string($title, COMPONENT),  array('class' => ' ml-0 mr-5 mb-10' ));
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);
        }
    }

    /**
     * Save contact files and update configuration
     * @param  array $oldconfig Old configuration saved in database
     * @param  array $newconfig New configuration submitted in form
     * @return array            Updated configuration
     */
    public function update_contact_files($oldconfig, $newconfig) {
        // This call to delete the existing files.
        $this->update_contact_file_area($oldconfig, true);
        // This call to save the files.
        return $this->update_contact_file_area($newconfig, false);
    }

    /**
     * Update file uploaded in contact form.
     * @param  array   $configdata Configuration data
     * @param  boolean $delete     If true then files from configuration will be deleted
     * @return array               If not deleting then return updated configuration
     */
    public function update_contact_file_area($configdata, $delete = true) {
        global $CFG, $OUTPUT;

        $configdata = $this->update_section_bg_file(SEC_CONTACT, $configdata, $delete);

        if ($delete) {
            return;
        }

        // Need to save updated configdata, that is why returning here.
        return $configdata;
    }

    /**
     * Create copy of all files used in contact for draft config
     * @param  array $configdata draft config data array
     * @return array             updated draft config
     */
    private function contact_duplicate_file_in_config($configdata) {
        global $CFG, $OUTPUT;

        $configdata = $this->duplicate_section_bg_file(SEC_CONTACT, $configdata);

        return $this->update_contact_file_area($configdata, false);
    }

    /**
     * Updated social icon styling parameter
     * @param  int    $id       instance id
     * @param  array  $formdata data submitted in form
     * @return array            updated form data
     */
    private function contact_process_form_submission($id, $formdata) {
        if (!isset($formdata['socialview'])) {
            $formdata['socialview'] = 'round';
        }
        return $formdata;
    }
}
