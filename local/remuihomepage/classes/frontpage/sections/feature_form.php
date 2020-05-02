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
define('SEC_FEATURE', 'section_feature');

trait feature_form {

    /**
     * Get the validation js script for feature form
     * @return string html string which contain js code for validation
     */
    private function feature_form_validation_js() {
        ob_start();
        ?>
        <script type="text/javascript">
            var remui_section_form_validate = function(root) {
                var valid = true;
                var scrolled = false;
                var scrollTo = function(element) {
                    if (!scrolled) {
                        element.closest('.modal-content').animate({
                            scrollTop: element.closest('.felement').position().top
                        }, 0);
                        scrolled = true;
                    }
                }
                var rows = $(root).find('#id_rows').val();
                var features;
                var element;
                for (var i = 0; i < rows; i++) {
                    features = $(root).find('#id_row_' + i + '_features').val();
                    for (var j = 0; j < features; j++) {
                        element = $(root).find('#id_row_' + i + '_feature_' + j + '_name_text');
                        if ($(element).val() == '') {
                            valid = false;
                            scrollTo(element);
                            element[0].dispatchEvent(new CustomEvent('blur'));
                        }
                        element = $(root).find('#id_row_' + i + '_feature_' + j + '_icon_text');
                        if ($(element).val() == '') {
                            scrollTo(element);
                            valid = false;
                            element[0].dispatchEvent(new CustomEvent('blur'));
                        }
                    }
                }
                return valid;
             }
        </script>
        <?php
        return ob_get_clean();
    }

    /**
     * Returns the Team form.
     * @param  stdClass &$mform   Form object.
     * @param  array    $formdata Form data.
     * @param  string   $config   Saved configuration
     * @return stdClass           Form object with data.
     */
    private function featureform(&$mform, $formdata, $configdata) {

        $mform->addElement('html', $this->feature_form_validation_js());

        $this->add_common_section_settings(
            $mform,
            $formdata['sectionproperties'],
            $configdata['sectionproperties'],
            SEC_FEATURE,
            DEFAULT_COMMON_SECTION_PROPERTIES,
            isset($formdata['fromform'])
        );

        $mform->addElement('header', 'moodle', get_string('feature', COMPONENT));

        // Title Setting.
        $title = 'title';
        $textobj = array(
                'label' => get_string($title, COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('titleplaceholder', COMPONENT),
                'required' => false
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $title = "description";
        $textobj = array(
                'label' => get_string($title, COMPONENT),
                'type' => 'text',
                'placeholder' => get_string('descriptionplaceholder', COMPONENT),
                'required' => false
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        // Text Alignment.
        $title = 'textalign';
        $options = ['text-center' => 'Center', 'text-right' => 'Right', 'text-left' => 'Left'];
        $formdata[$title] = (!isset($formdata[$title])) ? $options['text-center'] : $formdata[$title];
        $mform->addElement('select', $title, get_string($title, COMPONENT), $options, array('class' => ' ml-0 mr-5 mb-10' ));
        $mform->setDefault($title, $formdata[$title]);

        // Number of rows Setting.
        $rows = ['1' => 1, '2' => 2];
        $formdata["rows"] = (!isset($formdata['rows'])) ? $rows['1'] : $formdata['rows'];
        $mform->addElement('select', 'rows', get_string('rows', COMPONENT), $rows, array('class' => 'updateform ml-0 mr-5 mb-10' ));
        $mform->setDefault('rows', $formdata["rows"]);

        // Features Setting.
        for ($nrow = 0; $nrow < $formdata['rows']; $nrow++) {
            // As loop starts from 0, but we want to print 1 as Start point, this is to display only.
            $dispnrow = $nrow + 1;

            // Print the required moodle fields first.
            $mform->addElement('header', 'moodle', "Row ".$dispnrow);

            // Features Count Select Dropdown Setting.
            $rowscount = ['1' => 1, '2' => 2, '3' => 3, '4' => 4];
            $defaultval = (!isset($configdata['row'][$nrow]['features'])) ? $rowscount['1'] : $configdata['row'][$nrow]['features'];
            $title = 'row'.'_'.$nrow.'_features';
            $defaultval = (!isset($formdata['row'][$nrow]['features'])) ? $defaultval : $formdata['row'][$nrow]['features'];
            $title = 'row'.'_'.$nrow.'_features';
            $mform->addElement(
                'select',
                $title,
                get_string('features', COMPONENT),
                $rowscount,
                array('class' => 'updateform ml-0 mr-5 mb-10')
            );
            $mform->setDefault($title, $defaultval);
            $featurecount = $defaultval;

            for ($nfeature = 0; $nfeature < $featurecount; $nfeature++) {
                $dispnfeature = $nfeature + 1;
                $mform->addElement('header', 'moodle', "feature ".$dispnfeature);

                // Name Setting of $nfeature'th feature.
                $title = 'row' . '_' . $nrow .'_feature_'.$nfeature.'_'. 'name';
                $textobj = array(
                    'label' => get_string('name', COMPONENT),
                    'type' => 'text',
                    'placeholder' => get_string('featurenameplaceholder', COMPONENT),
                    'required' => true,
                    'requiredmsg' => get_string('missingname', COMPONENT),
                    'onlycolor' => true,
                );
                $defaultval = isset($configdata['row'][$nrow]['feature'][$nfeature]['name']) ?
                              $configdata['row'][$nrow]['feature'][$nfeature]['name'] : [];
                $defaultval = isset($formdata['row'][$nrow]['feature'][$nfeature]['name']) ?
                              $formdata['row'][$nrow]['feature'][$nfeature]['name'] : $defaultval;
                $this->add_section_title_settings($mform, $defaultval, $textobj, $title);

                // Icon Setting of $nfeature'th feature.

                $title = 'row' . '_' . $nrow .'_feature_'.$nfeature.'_'. 'icon';
                $textobj = array(
                    'label' => get_string('icon', COMPONENT),
                    'type' => 'text',
                    'placeholder' => get_string('featureiconplaceholder', COMPONENT),
                    'required' => true,
                    'requiredmsg' => get_string('missingicon', COMPONENT),
                    'onlycolor' => true,
                );
                $defaultval = isset($configdata['row'][$nrow]['feature'][$nfeature]['icon']) ?
                              $configdata['row'][$nrow]['feature'][$nfeature]['icon'] : [];
                $defaultval = isset($formdata['row'][$nrow]['feature'][$nfeature]['icon']) ?
                              $formdata['row'][$nrow]['feature'][$nfeature]['icon'] : $defaultval;
                $this->add_section_title_settings($mform, $defaultval, $textobj, $title);

                // Feature description.
                $title = 'row' . '_' . $nrow .'_feature_'.$nfeature.'_'. 'description';
                $textobj = array(
                    'label' => get_string('description', COMPONENT),
                    'type' => 'text',
                    'placeholder' => get_string('descriptionplaceholder', COMPONENT),
                    'required' => false,
                    'onlycolor' => true,
                );
                $defaultval = isset($configdata['row'][$nrow]['feature'][$nfeature]['description']) ?
                              $configdata['row'][$nrow]['feature'][$nfeature]['description'] : [];
                $defaultval = isset($formdata['row'][$nrow]['feature'][$nfeature]['description']) ?
                              $formdata['row'][$nrow]['feature'][$nfeature]['description'] : $defaultval;
                $this->add_section_title_settings($mform, $defaultval, $textobj, $title);

            }
        }
    }

    /**
     * Save feature files and update configuration
     * @param  array $oldconfig Old configuration saved in database
     * @param  array $newconfig New configuration submitted in form
     * @return array            Updated configuration
     */
    public function update_feature_files($oldconfig, $newconfig) {
        // This call to delete the existing files.
        $this->update_feature_file_area($oldconfig, true);
        // This call to save the files.
        return $this->update_feature_file_area($newconfig, false);
    }

    /**
     * Update file uploaded in feature form.
     * @param  array   $configdata Configuration data
     * @param  boolean $delete     If true then files from configuration will be deleted
     * @return array               If not deleting then return updated configuration
     */
    public function update_feature_file_area($configdata, $delete = true) {
        global $CFG, $OUTPUT;

        $configdata = $this->update_section_bg_file(SEC_FEATURE, $configdata, $delete);

        if ($delete) {
            return;
        }

        // Need to save updated configdata, that is why returning here.
        return $configdata;
    }

    /**
     * Create copy of all files used in feature for draft config
     * @param  array $configdata draft config data array
     * @return array             updated draft config
     */
    private function feature_duplicate_file_in_config($configdata) {
        global $CFG, $OUTPUT;

        $configdata = $this->duplicate_section_bg_file(SEC_FEATURE, $configdata);

        return $this->update_feature_file_area($configdata, false);
    }

}
