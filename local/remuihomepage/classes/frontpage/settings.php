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

namespace local_remuihomepage\frontpage;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir .'/formslib.php');

use local_remuihomepage\frontpage\sections\main_form as main_form;
use context_system;

class settings extends main_form {

    /**
     * Form definition.
     * @return void
     */
    public function definition() {

        $mform = $this->_form;
        $context = context_system::instance();
        $configdata = [];
        if (isset($this->_customdata->configdata)) {
            $configdata = $this->_customdata->configdata;
        }
        $options = array(
            'subdirs' => 0,
            'maxfiles' => 1,
            'accepted_types' => 'web_image'
        );
        // File picker to upload loader image.
        $title = 'frontpageloader';
        $loader = $mform->addElement(
            'filemanager',
            $title,
            get_string($title, 'local_remuihomepage'),
            array('class' => 'ml-0 mr-5 mb-10'),
            $options
        );

        if (isset($configdata[$title])) {
            $draftitemid = null;
            file_prepare_draft_area($draftitemid, $context->id, 'theme_remui', $title, 0);
            $loader->setValue($draftitemid);
        }

        // Setting to enable transparent header.
        $transparent = 'frontpagetransparentheader';
        $defaultval = (!isset($configdata[$transparent])) ? 0 : $configdata[$transparent];
        $mform->addElement(
            'checkbox',
            $transparent,
            get_string($transparent, 'local_remuihomepage'),
            get_string($transparent.'desc', 'local_remuihomepage'),
            array('class' => 'ml-0 mr-5 mb-10')
        );
        $mform->setDefault($transparent, $defaultval);

        // Setting to choose header color when it is transparent.
        $title = 'frontpageheadercolor';
        $defaultval = (!isset($configdata[$title]) && $configdata[$title] != '') ? '#ffffff' : $configdata[$title];
        $html = $this->get_color_picker(
            $title,
            $defaultval,
            "",
            get_string('frontpageheadercolor', 'local_remuihomepage'),
            get_string('frontpageheadercolordesc', 'local_remuihomepage')
        );
        $mform->addElement('html', $html);

        // Hide header color when transparent header is disabled.
        $mform->hideIf($title, $transparent, 'notchecked', 0);

        // Enable or disable section loading animation.
        $animation = $title = 'frontpageappearanimation';
        $defaultval = (!isset($configdata[$title])) ? 1 : $configdata[$title];
        $mform->addElement(
            'checkbox',
            $title,
            get_string($title, 'local_remuihomepage'),
            get_string($title.'desc', 'local_remuihomepage'),
            array('class' => 'ml-0 mr-5 mb-10')
        );
        $mform->setDefault($title, $defaultval);

        // Animation style.
        $title = 'frontpageappearanimationstyle';
        $options = array(
            'animation-fade' => get_string('fade', 'local_remuihomepage'),
            'animation-slide-bottom' => get_string('slide-bottom', 'local_remuihomepage'),
            'animation-slide-right' => get_string('slide-right', 'local_remuihomepage'),
            'animation-slide-left' => get_string('slide-left', 'local_remuihomepage'),
            'animation-slide-left-right' => get_string('slide-left-right', 'local_remuihomepage'),
            'animation-scale-up' => get_string('scale-up', 'local_remuihomepage'),
            'animation-scale-down' => get_string('scale-down', 'local_remuihomepage'),
        );
        $defaultval = (!isset($configdata[$title])) ? 'animation-slide-bottom' : $configdata[$title];
        $mform->addElement(
            'select',
            $title,
            get_string($title, 'local_remuihomepage'),
            $options,
            array('class' => 'ml-0 mr-5 mb-10 animation-style')
        );
        $mform->setDefault($title, $defaultval);

        // Hide animation style drop down if appear animation is disabled.
        $mform->hideIf($title, $animation, 'notchecked', 0);
    }
}
