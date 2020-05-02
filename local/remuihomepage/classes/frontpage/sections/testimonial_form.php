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
define('SEC_TESTIMONIAL', 'section_testimonial');
use context_system;

trait testimonial_form {

    /**
     * Get the validation js script for testimonial form
     * @return string html string which contain js code for validation
     */
    private function testimonial_form_validation_js() {
        ob_start();
        ?>
        <script type="text/javascript">
            var remui_section_form_validate = function(root) {
                var valid = true;
                var scrolled = false;
                var scrollTo = function(element) {
                    if (!scrolled) {
                        $(element).closest('.modal-content').animate({
                            scrollTop: $(element).closest('.felement').position().top
                        }, 0);
                        scrolled = true;
                    }
                }
                var count = $(root).find('#id_testimonials').val();
                var selectors = [];
                for (var i = 0; i < count; i++) {
                    selectors.push('#id_testimonial_' + i + '_name_text');
                    selectors.push('#id_testimonial_' + i + '_testimonial_text');
                }
                var elements = $(root).find(selectors.join());
                elements.each(function(index, element) {
                    if ($(element).val() == '') {
                        scrollTo(element);
                        valid = false;
                        element.dispatchEvent(new CustomEvent('blur'));
                    }
                });
                return valid;
             }
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Returns the Testimonial form.
     * @param  stdClass &$mform   Form object.
     * @param  array    $formdata Form data.
     * @param  string   $config   Saved configuration
     * @return stdClass           Form object with data.
     */
    private function testimonialform(&$mform, $formdata, $dbformdata) {
        global $USER;

        $mform->addElement('html', $this->testimonial_form_validation_js());

        $this->add_common_section_settings(
            $mform,
            $formdata['sectionproperties'],
            $dbformdata['sectionproperties'],
            SEC_TESTIMONIAL,
            DEFAULT_COMMON_SECTION_PROPERTIES,
            isset($formdata['fromform'])
        );

        $mform->addElement('header', 'moodle', "Testimonials ");

        // Title Setting.
        $title = 'title';
        $textobj = array(
            'label' => get_string($title, COMPONENT),
            'type' => 'text',
            'placeholder' => get_string('titleplaceholder', COMPONENT),
            'required' => false
        );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        // Description setting.
        $title = 'description';
        $textobj = array(
            'label' => get_string($title, COMPONENT),
            'type' => 'textarea',
            'placeholder' => get_string('descriptionplaceholder', COMPONENT),
            'required' => false
        );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $rows = array_combine(range(1, 10), range(1, 10));

        // Testimonial setting.
        $title = 'testimonials';
        $defaultval = (!isset($formdata[$title])) ? $rows[1] : $formdata[$title];
        $mform->addElement('select', $title, get_string($title, COMPONENT), $rows, array('class' => 'updateform ml-0 mr-5 mb-10'));
        $mform->setDefault($title, $defaultval);

        // Person testimonial properties.
        $title = "testimonialproperties";
        $textobj = array(
            'label' => get_string('testimonialproperties', COMPONENT),
            'type' => 'textarea',
            'placeholder' => get_string('testimonialpropertiesdesc', COMPONENT),
            'noinput' => true,
        );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        // Person name color.
        $title = 'namecolor';
        $defaultval = (!isset($formdata[$title])) ? '#7aa641' : $formdata[$title];
        $html = $this->get_color_picker(
            $title,
            $defaultval,
            '',
            get_string($title, COMPONENT),
            get_string($title.'desc', COMPONENT)
        );
        $mform->addElement('html', $html);

        // Person designation color.
        $title = 'designationcolor';
        $defaultval = (!isset($formdata[$title])) ? '#aaaaaa' : $formdata[$title];
        $html = $this->get_color_picker(
            $title,
            $defaultval,
            '',
            get_string($title, COMPONENT),
            get_string($title.'desc', COMPONENT)
        );
        $mform->addElement('html', $html);

        // Testimonial view.
        $view  = $title = 'view';
        $values = array(0 => get_string('layout1', COMPONENT), 1 => get_string('layout2', COMPONENT));
        $defaultval = (!isset($formdata[$title])) ? 1 : $formdata[$title];
        $mform->addElement('select', $title, get_string('view', COMPONENT), $values, array('class' => ' ml-0 mr-5 mb-10'));
        $mform->setType($title, PARAM_TEXT);
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

        for ($trow = 0; $trow < $formdata['testimonials']; $trow++) {

            $dispnrow = $trow + 1;

            // Print the required moodle fields first.
            $mform->addElement('header', 'moodle', "Testimonial ".$dispnrow);

            // Person name.
            $title = "testimonial_".$trow."_name";
            $textobj = array(
                'label' => get_string('fullname'),
                'type' => 'text',
                'placeholder' => get_string('fullnameplaceholder', COMPONENT),
                'required' => true,
                'requiredmsg' => get_string('missingfullname'),
                'noattrib' => true,
            );
            $defaultval = isset($dbformdata['testimonial'][$trow]['name']) ?
                          $dbformdata['testimonial'][$trow]['name'] : [];
            $defaultval = isset($formdata['testimonial'][$trow]['name']) ?
                          $formdata['testimonial'][$trow]['name'] : $defaultval;
            $this->add_section_title_settings($mform, $defaultval, $textobj, $title);

            // Person designation.
            $title = "testimonial_".$trow."_designation";
            $textobj = array(
                'label' => get_string('designation', COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('designationplaceholder', COMPONENT),
                'noattrib' => true,
            );
            $defaultval = isset($dbformdata['testimonial'][$trow]['designation']) ?
                          $dbformdata['testimonial'][$trow]['designation'] : [];
            $defaultval = isset($formdata['testimonial'][$trow]['designation']) ?
                          $formdata['testimonial'][$trow]['designation'] : $defaultval;
            $this->add_section_title_settings($mform, $defaultval, $textobj, $title);

            // Person testimonial.
            $title = "testimonial_".$trow."_testimonial";
            $textobj = array(
                'label' => get_string('testimonial', COMPONENT),
                'type' => 'textarea',
                'placeholder' => get_string('testimonialplaceholder', COMPONENT),
                'required' => true,
                'requiredmsg' => get_string('missingtestimonial', COMPONENT),
                'noattrib' => true,
            );
            $defaultval = isset($dbformdata['testimonial'][$trow]['testimonial']) ?
                          $dbformdata['testimonial'][$trow]['testimonial'] : [];
            $defaultval = isset($formdata['testimonial'][$trow]['testimonial']) ?
                          $formdata['testimonial'][$trow]['testimonial'] : $defaultval;
            $this->add_section_title_settings($mform, $defaultval, $textobj, $title);

            // Member Image.
            $draftitemid = null;
            $itemid = null;
            $context = \context_system::instance();
            $title = "testimonial_".$trow."_image";
            // This will load image from new config, but check that it is not the same itemid from db and new config.
            // Because at first time, formdata = dbformdata.
            $formitemid = isset($formdata['testimonial'][$trow]['image']) ?
                          $formdata['testimonial'][$trow]['image'] : null;
            $dbitemid = isset($dbformdata['testimonial'][$trow]['image']) ?
                          $dbformdata['testimonial'][$trow]['image'] : null;

            if (($formitemid == null && $dbitemid != null) || ($formitemid != null && $formitemid == $dbitemid)) {
                $itemid = $dbitemid;
            } else if ($formitemid != null && $formitemid != $dbitemid) {
                $draftitemid = $formitemid;
            }

            if (!isset($draftitemid) && isset($itemid)) {
                // Load the file from database to draft area.
                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    THEME_COMPONENT,
                    SEC_TESTIMONIAL,
                    $itemid,
                    array(
                        'subdirs' => 0,
                        'maxfiles' => 1
                    )
                );
            }

            $image = $mform->addElement(
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
            );
            if (isset($draftitemid)) {
                $image->setValue($draftitemid);
            }
        }
    }

    /**
     * Update testimonial files
     * @param  array $oldconfig Old configuration of section
     * @param  array $newconfig New configuration submitted in the form
     * @return array            Array of section configuration data
     */
    public function update_testimonial_files($oldconfig, $newconfig) {
        // This call to delete the files.
        $this->update_testimonial_file_area($oldconfig, true);
        // This call to save the files.
        return $this->update_testimonial_file_area($newconfig, false);
    }

    /**
     * Update testimonial files in testimonial file area or delete if delete parameter is set
     * @param  array   $configdata Configuration data
     * @param  boolean $delete     true if need to delete from congfigdata
     * @return array               Configuration data
     */
    public function update_testimonial_file_area($configdata, $delete = true) {
        global $CFG, $OUTPUT;
        require_once($CFG->dirroot . "/theme/remui/lib.php");

        $fs = get_file_storage();
        $context = context_system::instance();

        $statusflag = true;
        foreach ($configdata['testimonial'] as $trow => $vrow) {
            $itemid = $vrow['image'];
            if ($itemid != "" || $itemid != null) {
                if ($delete) {
                    $fs->delete_area_files($context->id, THEME_COMPONENT, SEC_TESTIMONIAL, $itemid);
                } else {
                    $newitemid = theme_remui_get_unused_itemid(SEC_TESTIMONIAL);
                    file_save_draft_area_files($itemid, $context->id, THEME_COMPONENT, SEC_TESTIMONIAL, $newitemid);
                    $configdata['testimonial'][$trow]['image'] = $newitemid;
                    $imgurl = get_file_img_url($newitemid, THEME_COMPONENT, SEC_TESTIMONIAL);
                    if ($imgurl == "") {
                        $imgurl = $OUTPUT->image_url('u/f2')->out();
                    }
                    $configdata['testimonial'][$trow]['imageurl'] = $imgurl;
                    if ($statusflag) {
                        $configdata['testimonial'][$trow]['status'] = $statusflag;
                        $statusflag = false;
                    }
                    $this->delete_draft_file($itemid);
                }
            }
        }

        $configdata = $this->update_section_bg_file(SEC_TESTIMONIAL, $configdata, $delete);

        if ($delete) {
            // While deleting no need to return anything.
            return;
        }
        // This is to disable navigation when count is 1.
        $configdata['enablenav'] = true;
        if ($configdata['testimonials'] == 1) {
            unset($configdata['enablenav']);
        }
        // Need to save updated configdata, that is why returning here.
        return $configdata;
    }

    /**
     * Create copy of all files used in testimonial for draft config
     * @param  array $configdata draft config data array
     * @return array             updated draft config
     */
    private function testimonial_duplicate_file_in_config($configdata) {
        global $CFG, $OUTPUT;

        $context = context_system::instance();
        if ($configdata['testimonials'] == 0) {
            return;
        }
        foreach ($configdata['testimonial'] as $trow => $vrow) {
            $itemid = $vrow['image'];
            if ($itemid != "" || $itemid != null) {
                $draftitemid = null;
                file_prepare_draft_area(
                    $draftitemid,
                    $context->id,
                    THEME_COMPONENT,
                    SEC_TESTIMONIAL,
                    $itemid,
                    array(
                        'subdirs' => 0,
                        'maxfiles' => 1
                    )
                );
                $configdata['testimonial'][$trow]['image'] = $draftitemid;
            }
        }

        $configdata = $this->duplicate_section_bg_file(SEC_TESTIMONIAL, $configdata);

        return $this->update_testimonial_file_area($configdata, false);
    }

    /**
     * Create testimonial member images before saving first instance.
     * @param  int    $id         instance id
     * @param  array  $configdata Default section data
     * @return array              updated form data
     */
    private function testimonial_process_section_creation($id, $configdata) {
        global $CFG, $USER;
        $fs = get_file_storage();
        $img = $configdata['testimonial'][0]['imageurl'];
        $filename = @end(explode('/', $img));
        $filepath = $CFG->dirroot . '/local/remuihomepage/pix/' . $img;
        $itemid = file_get_unused_draft_itemid();
        $record = [
            'contextid' => \context_user::instance($USER->id)->id,
            'component' => 'user',
            'filearea'  => 'draft',
            'itemid'    => $itemid,
            'filepath'  => '/',
            'filename'  => $filename,
        ];
        $file = $fs->create_file_from_pathname($record, $filepath);
        $configdata['testimonial'][0]['image'] = $itemid;
        return $this->update_testimonial_file_area($configdata, false);
    }

    /**
     * Apply name, designation color and process testimonial properties
     * @param  int    $id       instance id
     * @param  array  $formdata data submitted in form
     * @return array            updated form data
     */
    private function testimonial_process_form_submission($id, $formdata) {
        if (isset($formdata['slideinterval'])
            && $formdata['slideinterval'] <= 0
            || !isset($formdata['slideinterval'])
            || !is_numeric($formdata['slideinterval'])
        ) {
            $formdata['slideinterval'] = 5000;
        }
        $formdata['view'] = $formdata['view'] == 1;
        $formdata['hastitledescription'] = !empty($formdata['title']['text']) || !empty($formdata['description']['text']);
        foreach ($formdata['testimonial'] as $key => $testimonial) {
            $testimonial['testimonial']['textbold'] = isset($formdata['testimonialproperties']['textbold']);
            $testimonial['testimonial']['textitalic'] = isset($formdata['testimonialproperties']['textitalic']);
            $testimonial['testimonial']['textunderline'] = isset($formdata['testimonialproperties']['textunderline']);
            $testimonial['testimonial']['fontsize'] = $formdata['testimonialproperties']['fontsize'];
            $testimonial['testimonial']['color'] = $formdata['testimonialproperties']['color'];
            $formdata['testimonial'][$key]['testimonial'] = $testimonial['testimonial'];
        }
        return $formdata;
    }

}
