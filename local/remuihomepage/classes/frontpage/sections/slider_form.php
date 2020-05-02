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
define('SEC_SLIDER', 'section_slider');
use context_system;

trait slider_form {

    /**
     * Get the validation js script for slider form
     * @return string html string which contain js code for validation
     */
    private function slider_form_validation_js() {
        ob_start();
        ?>
        <script type="text/javascript">
            var remui_section_form_validate = function(root) {
                var valid = true;
                var count = $(root).find('#id_slides').val();
                var scrolled = false;
                var scrollTo = function(element) {
                    if (!scrolled) {
                        element.closest('.modal-content').animate({
                            scrollTop: element.closest('.felement').position().top
                        }, 0);
                        scrolled = true;
                    }
                }
                var element;
                for (var i = 0; i < count; i++) {
                    element = $(root).find('#id_slide_' + i + '_fileitemid');
                    if (element.siblings('.filemanager').hasClass('fm-nofiles')) {
                        element.siblings('.form-control-feedback').show().text(
                            M.util.get_string('missingslide', '<?php echo COMPONENT; ?>')
                        );
                        element.closest('.felement').addClass('has-danger');
                        scrollTo(element);
                        valid = false;
                    } else {
                        element.siblings('.form-control-feedback').hide()
                        element.closest('.felement').removeClass('has-danger');
                    }
                }
                return valid;
             }
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Returns the slider form.
     * @param  stdClass &$mform   Form object.
     * @param  array    $formdata Form data.
     * @param  string   $config   Saved configuration
     * @return stdClass           Form object with data.
     */
    private function sliderform(&$mform, $formdata, $dbformdata) {
        global $USER, $PAGE;

        $PAGE->requires->strings_for_js(['missingslide'], COMPONENT);

        $this->add_common_section_settings(
            $mform,
            isset($formdata['sectionproperties']) ? $formdata['sectionproperties'] : [],
            isset($configdata['sectionproperties']) ? $configdata['sectionproperties'] : [],
            SEC_SLIDER,
            array('customcss' => true)
        );

        $mform->addElement('html', $this->slider_form_validation_js());

        $mform->addElement('header', 'moodle', "Slider ");
        // Number of slides.

        $nslides = array_combine(range(1, 10), range(1, 10));
        $title = 'slides';
        $formdata["slides"] = (!isset($formdata['slides'])) ? $nslides['1'] : $formdata['slides'];
        $mform->addElement(
            'select',
            $title,
            get_string('noofslides', COMPONENT),
            $nslides,
            array('class' => 'updateform ml-0 mr-5 mb-10')
        );
        $mform->setDefault($title, $formdata['slides']);

        $title = 'enablenav';
        $options = [
            'nonav' => get_string('nonav', COMPONENT),
            'navarrows' => get_string('navarrows', COMPONENT),
            'navarrowscircle' => get_string('navarrowscircle', COMPONENT),
            'navarrowssquare' => get_string('navarrowssquare', COMPONENT)];
        $defaultval = (!isset($formdata['enablenav'])) ? $options['navarrows'] : $formdata['enablenav'];
        $mform->addElement('select', $title, get_string('enablenav', COMPONENT), $options, array('class' => ' ml-0 mr-5 mb-10' ));
        $mform->setDefault($title, $defaultval);

        // Slide interval.
        $title = 'slideinterval';
        $defaultval = (!isset($formdata['slideinterval'])) ? '5000' : $formdata['slideinterval'];
        $mform->addElement('text', $title, get_string($title, COMPONENT), array(
            'class' => ' ml-0 mr-5 mb-10',
            'placeholder' => get_string($title.'placeholder', COMPONENT)
        ));
        $mform->setDefault($title, $defaultval);
        $mform->setType($title, PARAM_INT);

        // Add slide interval description.
        $mform->addElement('html', $this->get_description(get_string($title.'desc', COMPONENT)));

        for ($nslide = 0; $nslide < $formdata['slides']; $nslide++) {
            $dispnrow = $nslide + 1;
            // Print the required moodle fields first.
            $mform->addElement('header', 'moodle', "Slide ".$dispnrow);

            $title = 'slide' . '_' . $nslide .'_fileitemid';
            $draftitemid = null;

            // This will load image to draft from db config.
            if (isset($dbformdata['slide'][$nslide]['fileitemid'])) {
                $context = \context_system::instance();
                $itemid = $dbformdata['slide'][$nslide]['fileitemid'];

                    // Load the file from database to draft area.
                    file_prepare_draft_area(
                        $draftitemid,
                        $context->id,
                        THEME_COMPONENT,
                        SEC_SLIDER,
                        $itemid,
                        array(
                            'subdirs' => 0,
                            'maxfiles' => 1
                        )
                    );
            }

            // This will load image from new config, but check that it is not the same itemid from db and new config.
            // Because at first time, formdata = dbformdata.
            if (isset($formdata['slide'][$nslide]['fileitemid'])
                && $formdata['slide'][$nslide]['fileitemid']
                != $dbformdata['slide'][$nslide]['fileitemid']
            ) {
                $context = \context_system::instance();
                $itemid = $formdata['slide'][$nslide]['fileitemid'];

                $fs = get_file_storage();
                $usercontext = \context_user::instance($USER->id);
                if ($this->is_file_exist_in_area($fs, $usercontext, $itemid, 'user', 'draft') != null) {
                    $draftitemid = $itemid;
                }
            }

            $mform->addElement(
                'filemanager',
                $title,
                get_string('imageorvideo', COMPONENT),
                array('class' => ' ml-0 mr-5 mb-10' ),
                array(
                    'subdirs' => 0,
                    'maxbytes' => 2048,
                    'areamaxbytes' => 10482880,
                    'maxfiles' => 1,
                    'accepted_types' => array('web_image', '.mp4')
                )
            )->setValue($draftitemid);
            $mform->addRule($title, get_string('missingslide', COMPONENT), 'required', null, 'client');

            // Number of slides.
            $title = 'slide_'.$nslide.'_textalign';
            $options = array('center' => "Center", 'left' => "Left", 'right' => "Right");
            $defaultval = "center";
            $defaultval = (!isset($dbformdata['slide'][$nslide]['headingcolor'])) ?
                          $defaultval : $dbformdata['slide'][$nslide]['headingcolor'];
            $defaultval = (!isset($formdata['slide'][$nslide]['textalign'])) ?
                          $defaultval : $formdata['slide'][$nslide]['textalign'];
            $mform->addElement(
                'select',
                $title,
                get_string('textalign', COMPONENT),
                $options,
                array('class' => ' ml-0 mr-5 mb-10')
            );
            $mform->setDefault($title, $defaultval);

            // Slider Heading.
            $title = 'slide_'.$nslide.'_heading';

            // Default empty value.
            $defaultval = "";
            // Set value if db has value saved for this field.
            $defaultval = (!isset($dbformdata['slide'][$nslide]['heading'])) ?
                          $defaultval : $dbformdata['slide'][$nslide]['heading'];
            // On Config Update, Update the default value from current updated data.
            $defaultval = (!isset($formdata['slide'][$nslide]['heading'])) ?
                          $defaultval : $formdata['slide'][$nslide]['heading'];
            $mform->addElement('text', $title, get_string('slideheading', COMPONENT), array(
                'class' => 'ml-0 mr-5 mb-10',
                'placeholder' => get_string('slideheadingplaceholder', COMPONENT)
            ));
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);

            $title = 'slide_'.$nslide.'_headingcolor';
            $defaultval = "";
            $defaultval = (!isset($dbformdata['slide'][$nslide]['headingcolor'])) ?
                          $defaultval : $dbformdata['slide'][$nslide]['headingcolor'];
            $defaultval = (!isset($formdata['slide'][$nslide]['headingcolor'])) ?
                          $defaultval : $formdata['slide'][$nslide]['headingcolor'];
            $html = $this->get_color_picker($title, $defaultval);
            $mform->addElement('html', $html);
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);

            // Member Quote.
            $title = 'slide_'.$nslide.'_description';
            $defaultval = "";
            $defaultval = (!isset($dbformdata['slide'][$nslide]['description'])) ?
                          $defaultval : $dbformdata['slide'][$nslide]['description'];
            $defaultval = (!isset($formdata['slide'][$nslide]['description'])) ?
                          $defaultval : $formdata['slide'][$nslide]['description'];
            $mform->addElement('textarea', $title, get_string('slidedescription', COMPONENT), array(
                'class' => ' ml-0 mr-5 mb-10',
                'placeholder' => get_string('slidedescriptionplaceholder', COMPONENT)
            ));
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);

            $title = 'slide_'.$nslide.'_desccolor';
            $defaultval = "";
            $defaultval = (!isset($dbformdata['slide'][$nslide]['desccolor'])) ?
                          $defaultval : $dbformdata['slide'][$nslide]['desccolor'];
            $defaultval = (!isset($formdata['slide'][$nslide]['desccolor'])) ?
                          $defaultval : $formdata['slide'][$nslide]['desccolor'];
            $html = $this->get_color_picker($title, $defaultval);
            $mform->addElement('html', $html);
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);

            $link = $title = 'slide_'.$nslide.'_btnlink';
            $defaultval = "";
            $defaultval = (!isset($dbformdata['slide'][$nslide]['btnlink'])) ?
                          $defaultval : $dbformdata['slide'][$nslide]['btnlink'];
            $defaultval = (!isset($formdata['slide'][$nslide]['btnlink'])) ?
                          $defaultval : $formdata['slide'][$nslide]['btnlink'];
            $mform->addElement('text', $title, get_string('btnlink', COMPONENT), array(
                'class' => ' ml-0 mr-5 mb-10',
                'placeholder' => get_string('btnlinkplaceholder', COMPONENT)
            ));
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);

            $label = $title = 'slide_'.$nslide.'_btnlabel';
            $defaultval = "";
            $defaultval = (!isset($dbformdata['slide'][$nslide]['btnlabel'])) ?
                          $defaultval : $dbformdata['slide'][$nslide]['btnlabel'];
            $defaultval = (!isset($formdata['slide'][$nslide]['btnlabel'])) ? $defaultval : $formdata['slide'][$nslide]['btnlabel'];
            $mform->addElement('text', $title, get_string('btnlabel', COMPONENT), array(
                'class' => ' ml-0 mr-5 mb-10',
                'placeholder' => get_string('btnlabelplaceholder', COMPONENT)
            ));
            $mform->setType($title, PARAM_TEXT);
            $mform->setDefault($title, $defaultval);
        }

    }

    /**
     * Update slider files
     * @param  array $oldconfig Old configuration of section
     * @param  array $newconfig New configuration submitted in the form
     * @return array            Array of section configuration data
     */
    public function update_slider_files($oldconfig, $newconfig) {
        // This call to delete the files.
        $this->update_slider_file_area($oldconfig, true);
        // This call to save the files.
        return $this->update_slider_file_area($newconfig, false);
    }

    /**
     * Update slider files in slider file area or delete if delete parameter is set
     * @param  array   $configdata Configuration data
     * @param  boolean $delete     true if need to delete from congfigdata
     * @return array               Configuration data
     */
    public function update_slider_file_area($configdata, $delete = true) {
        global $CFG;
        require_once($CFG->dirroot . "/theme/remui/lib.php");

        $statusflag = true;
        $fs = get_file_storage();
        $context = context_system::instance();
        $index = 0;
        foreach ($configdata['slide'] as $kslide => $vslide) {
            $itemid = $vslide['fileitemid'];
            if ($itemid != "" || $itemid != null) {
                if ($delete) {
                    $fs->delete_area_files($context->id, THEME_COMPONENT, SEC_SLIDER, $itemid);
                } else {
                    $newitemid = theme_remui_get_unused_itemid(SEC_SLIDER);
                    file_save_draft_area_files($itemid, $context->id, THEME_COMPONENT, SEC_SLIDER, $newitemid);
                    $configdata['slide'][$kslide]['fileitemid'] = $newitemid;
                    $configdata['slide'][$kslide]['fileurl'] = get_file_img_url($newitemid, THEME_COMPONENT, SEC_SLIDER);
                    $mimetype = $this->get_file_type($fs, $context, $newitemid, SEC_SLIDER);
                    $configdata['slide'][$kslide]['image'] = stripos($mimetype, 'image') !== false;
                    $configdata['slide'][$kslide]['video'] = stripos($mimetype, 'video') !== false;
                    $configdata['slide'][$kslide]['index'] = $index++;
                    if ($statusflag) {
                        if ($configdata['slide'][$kslide]['fileurl'] != "" || $configdata['slide'][$kslide]['fileurl'] != null) {
                            $configdata['slide'][$kslide]['status'] = $statusflag;
                            $statusflag = false;
                        }
                    }
                    $this->delete_draft_file($itemid);
                }
            }
        }

        if ($delete) {
            // While deleting no need to return anything.
            return;
        }
        // Need to save updated configdata, that is why returning here.
        return $configdata;
    }

    /**
     * Create copy of all files used in slider for draft config
     * @param  array $configdata draft config data array
     * @return array             updated draft config
     */
    private function slider_duplicate_file_in_config($configdata) {
        global $CFG, $OUTPUT;

        $context = context_system::instance();
        foreach ($configdata['slide'] as $kslide => $vslide) {
            $itemid = $vslide['fileitemid'];
            if ($itemid != "" || $itemid != null) {
                $draftitemid = null;
                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    THEME_COMPONENT,
                    SEC_SLIDER,
                    $itemid,
                    array(
                        'subdirs' => 0,
                        'maxfiles' => 1
                    )
                );
                $configdata['slide'][$kslide]['fileitemid'] = $draftitemid;
            }
        }

        return $this->update_slider_file_area($configdata, false);
    }

    /**
     * Create slider image before saving first instance.
     * @param  int    $id         instance id
     * @param  array  $configdata Default section data
     * @return array              updated form data
     */
    private function slider_process_section_creation($id, $configdata) {
        global $CFG, $USER;
        $fs = get_file_storage();
        $slide = $configdata['slide'][0]['fileurl'];
        $filepath = $CFG->dirroot . '/local/remuihomepage/pix/' . $slide;
        $itemid = file_get_unused_draft_itemid();
        $record = [
            'contextid' => \context_user::instance($USER->id)->id,
            'component' => 'user',
            'filearea'  => 'draft',
            'itemid'    => $itemid,
            'filepath'  => '/',
            'filename'  => $slide,
        ];
        $file = $fs->create_file_from_pathname($record, $filepath);
        $configdata['slide'][0]['fileitemid'] = $itemid;
        return $this->update_slider_file_area($configdata, false);
    }

    /**
     * Process slider form data before saving
     * @param  int    $id       instance id
     * @param  array  $formdata data submitted in form
     * @return array            updated form data
     */
    private function slider_process_form_submission($id, $formdata) {
        if (isset($formdata['slideinterval']) && $formdata['slideinterval'] <= 0) {
            unset($formdata['slideinterval']);
        }
        return $formdata;
    }

}
