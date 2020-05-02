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
define('SEC_COURSES', 'section_courses');

if (!class_exists('core_course_category')) {
    require_once($CFG->libdir. '/coursecatlib.php');
}

trait courses_form {

    /**
     * Returns the courses form.
     * @param  stdClass &$mform     Form object.
     * @param  array    $formdata   Form data.
     * @return stdClass             Form object with data.
     */
    private function coursesform(&$mform, $formdata, $configdata) {

        $this->add_common_section_settings(
            $mform,
            $formdata['sectionproperties'],
            $configdata['sectionproperties'],
            SEC_COURSES,
            DEFAULT_COMMON_SECTION_PROPERTIES
        );

        $mform->addElement('header', 'moodle', "Courses ");

        // Title Setting.
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
                'required' => false,
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $title = 'show';
        $defaultval = (!isset($formdata[$title])) ? "" : $formdata[$title];
        $options = array(
            'courses' => get_string('courses', THEME_COMPONENT),
            'categories' => get_string('categories', COMPONENT),
            'categoryandcourses' => get_string('categoryandcourses', COMPONENT),
        );
        $mform->addElement('select', $title, get_string($title, COMPONENT), $options, array('class' => ' ml-0 mr-5 mb-10'));
        $mform->setType($title, PARAM_TEXT);
        $mform->setDefault($title, $defaultval);

        $title = 'date';
        $defaultval = (!isset($formdata[$title])) ? "" : $formdata[$title];
        $dates = array(
            'all' => get_string('all', 'local_remuihomepage'),
            'inprogress' => get_string('coursessectioninprogress', 'local_remuihomepage'),
            'future' => get_string('future', 'local_remuihomepage'),
            'past' => get_string('past', 'local_remuihomepage')
        );
        $mform->addElement('select', $title, get_string($title), $dates, array('class' => ' ml-0 mr-5 mb-10'));
        $mform->setType($title, PARAM_TEXT);
        $mform->setDefault($title, $defaultval);

        $title = 'categories';
        $defaultval = (!isset($formdata[$title])) ? "" : $formdata[$title];
        if (class_exists('core_course_category')) {
            $categories = \core_course_category::make_categories_list('moodle/course:create');
        } else if (class_exists('coursecat')) {
            $categories = \coursecat::make_categories_list('moodle/course:create');
        } else {
            return;
        }
        $options = array(
            'multiple' => true,
            'placeholder' => get_string('coursecategoriesplaceholder', COMPONENT),
            'class' => ' ml-0 mr-5 mb-10 frontpage-courses-form-categories-list'
        );
        $mform->addElement('autocomplete', 'categories', get_string('coursecategory'), $categories, $options);
        $mform->setDefault($title, $defaultval);
    }

    /**
     * Save courses files and update configuration
     * @param  array $oldconfig Old configuration saved in database
     * @param  array $newconfig New configuration submitted in form
     * @return array            Updated configuration
     */
    public function update_courses_files($oldconfig, $newconfig) {
        // This call to delete the existing files.
        $this->update_courses_file_area($oldconfig, true);
        // This call to save the files.
        return $this->update_courses_file_area($newconfig, false);
    }

    /**
     * Update file uploaded in courses form.
     * @param  array   $configdata Configuration data
     * @param  boolean $delete     If true then files from configuration will be deleted
     * @return array               If not deleting then return updated configuration
     */
    public function update_courses_file_area($configdata, $delete = true) {
        global $CFG, $OUTPUT;

        $configdata = $this->update_section_bg_file(SEC_COURSES, $configdata, $delete);

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
    private function courses_duplicate_file_in_config($configdata) {
        global $CFG, $OUTPUT;

        $configdata = $this->duplicate_section_bg_file(SEC_COURSES, $configdata);

        return $this->update_courses_file_area($configdata, false);
    }

    public function courses_process_form_submission($id, $formdata) {
        if (!isset($formdata['categories']) || is_string($formdata['categories'])) {
            $formdata['categories'] = [];
        }
        return $formdata;
    }
}
