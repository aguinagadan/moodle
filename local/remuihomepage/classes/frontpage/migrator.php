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

use context_system;
use moodle_url;
use local_remuihomepage\frontpage\sections\main_form as main_form;

class migrator extends section_manager {

    private $context = null;
    private $fs = null;

    /**
     * Initialise context and fs object because it will be used in most places
     */
    public function __construct() {
        $this->context = context_system::instance();
        $this->fs = get_file_storage();
    }

    /**
     * Create section form the given configuration
     * @param  array $section Section configuration
     * @return int            section instance id
     */
    public function create_section($section) {
        global $DB;
        $section['configdata'] = json_encode($section['configdata']);
        $section['visible'] = true;
        $section = (object) $section;
        $section->timecreated = $section->timemodified = time();
        $section->id = $DB->insert_record($this->get_sectiontable(), $section);
        $mainform = new main_form(null, (object)[
            'dontinitialize' => true,
            'instanceid' => $section->id,
            'formdata' => json_decode($section->configdata, true)
        ]);
        $section->configdata = json_encode($mainform->process_form_submission(false));
        $DB->update_record($this->get_sectiontable(), $section);
        return $section->id;
    }

    /**
     * Get file url for the file object
     * @param  stored_file $file Stored file object
     * @return string            File url
     */
    private function get_file_url($file) {
        return moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename(),
            false
        )->out();
    }

    /**
     * Get file uploaded in remui setting
     * @param  string               $filearea     Area of file
     * @param  string               $relativepath filename with file path
     * @return stored_file|boolean                Stored file object or false if file not exists
     */
    private function get_setting_file($filearea, $relativepath) {
        $fullpath = "/{$this->context->id}/theme_remui/$filearea/0$relativepath";
        $fullpath = rtrim($fullpath, '/');
        return $this->fs->get_file_by_hash(sha1($fullpath));
    }

    /**
     * Create file from remui settings
     * @param  string           $setting Setting name
     * @param  string           $newarea area for new file
     * @return stored_file|null          Stored file object or null if file not exists
     */
    private function create_file_from_settings($setting, $newarea) {
        $file = get_config('theme_remui', $setting);
        if ($file != '' && $file = $this->get_setting_file($setting, $file)) {
            $filerecord = [
                'contextid' => $this->context->id,
                'component' => 'theme_remui',
                'filearea'  => $newarea,
                'itemid'    => theme_remui_get_unused_itemid($newarea),
                'filepath'  => '/',
                'filename'  => $file->get_filename(),
            ];
            return $this->fs->create_file_from_storedfile($filerecord, $file);
        }
        return null;
    }

    /**
     * This function will get file from slider setting. If file is not exist then it will return passed slide.
     * If file is present then it will check for image or video.
     * @param  array  $slide   Slide configuration array
     * @param  string $setting Setting name
     * @return array           Slide configuration array
     */
    private function get_slider_image_video_from_settings($slide, $setting) {
        $file = $this->create_file_from_settings($setting, 'section_slider');
        if (!$file) {
            return false;
        }
        $slide['image'] = stripos($file->get_mimetype(), 'image') !== false;
        $slide['video'] = stripos($file->get_mimetype(), 'video') !== false;
        $slide['fileitemid'] = $file->get_itemid();
        $slide['fileurl'] = $this->get_file_url($file);
        return $slide;
    }

    /**
     * Add image and image url value in the object if file exist and file is image
     * @param  array             $object  Section configuration array
     * @param  stored_file $file          Stored file object
     * @return array                      Section configuration array
     */
    private function add_image_to_object($object, $file) {
        if ($file && stripos($file->get_mimetype(), 'image') !== false) {
            $object['image'] = $file->get_itemid();
            $object['imageurl'] = $this->get_file_url($file);
        }
        return $object;
    }

    /**
     * Create slide from the given parameter
     * @param  string  $description Slide description
     * @param  string  $image       Slide image setting name
     * @param  string  $btnlabel    Slide button label
     * @param  string  $btnlink     Slide button link
     * @param  boolean $status      Slide active status
     * @return array                Slide configuration array
     */
    private function create_slide($slide, $description, $image, $btnlabel = '', $btnlink = '', $status = true) {
        $slide['status']      = $status;
        $slide['btnlink']     = $btnlink;
        $slide['btnlabel']    = $btnlabel;
        $slide['description'] = $description;
        $slide = $this->get_slider_image_video_from_settings($slide, $image);
        return $slide;
    }

    /**
     * Create slider from older configs
     * @return int Section id
     */
    private function create_slider_from_older_configs() {
        $frontpageimagecontent = get_config('theme_remui', 'frontpageimagecontent');
        $section = $this->section_configuration('slider');
        $slides = [];
        $defaultslide = $section['configdata']['slide'][0];
        if ($frontpageimagecontent == 0) {
            $contenttype = get_config('theme_remui', 'contenttype');
            if ($contenttype == 0) {
                return false;
            }
            $slide = $this->create_slide(
                $defaultslide,
                strip_tags(get_config('theme_remui', 'addtext')),
                'staticimage'
            );
            if ($slide !== false) {
                $slides[] = $slide;
            }
        } else {
            $slidercount = get_config('theme_remui', 'slidercount');
            for ($i = 1; $i <= $slidercount; $i++) {
                $slide = $this->create_slide(
                    $defaultslide,
                    strip_tags(get_config('theme_remui', 'slidertext'. $i)),
                    'slideimage' . $i,
                    get_config('theme_remui', 'sliderbuttontext' . $i),
                    get_config('theme_remui', 'sliderurl' . $i),
                    $i == 1
                );
                if ($slide !== false) {
                    $slides[] = $slide;
                }
            }
        }
        $section['configdata']['slides'] = count($slides);
        $section['configdata']['slide'] = $slides;
        return $this->create_section($section);
    }

    /**
     * Create about us from older configs
     * @return int About us section id
     */
    private function create_aboutus_from_older_configs() {
        $section = $this->section_configuration('aboutus');
        $aboutus = get_config('theme_remui', 'frontpageblockdisplay');
        if ($aboutus == 1) {
            return false;
        }
        $blocks = $section['configdata']['block'];
        $section['configdata']['title']['text'] = get_config('theme_remui', 'frontpageblockheading');
        $section['configdata']['description']['text'] = strip_tags(get_config('theme_remui', 'frontpageblockdesc'));
        $section['configdata']['view'] = $aboutus - 1;
        $defaulticons = ['flag', 'globe', 'cog', 'users'];
        for ($i = 0; $i < 4; $i++) {
            $blocks[$i]['title']['text'] = get_config('theme_remui', 'frontpageblocksection' . ($i + 1));
            $blocks[$i]['description']['text'] = strip_tags(
                get_config('theme_remui', 'frontpageblockdescriptionsection' . ($i + 1))
            );
            $conf = get_config('theme_remui', 'frontpageblockiconsection' . ($i + 1));
            $blocks[$i]['icon'] = 'fa fa-' . ($conf != '' ? $conf : $defaulticons[$i]);
            $blocks[$i]['btnlink'] = get_config('theme_remui', 'sectionbuttonlink' . ($i + 1));
            $blocks[$i]['btnlabel'] = get_config('theme_remui', 'sectionbuttontext' . ($i + 1));
            $file = $this->create_file_from_settings('frontpageblockimage' . ($i + 1), 'section_aboutus');
            $blocks[$i] = $this->add_image_to_object($blocks[$i], $file, 0, '');
        }
        $section['configdata']['block'] = $blocks;
        return $this->create_section($section);
    }

    /**
     * Verify testimonial settings are valid to add in section
     * @param  int  $index Testimonial setting index
     * @return bool        True if settings are valid else false
     */
    private function verify_testimonial_settings($index) {
        if (get_config('theme_remui', 'testimonialname' . ($index)) == '') {
            return false;
        }
        if (strip_tags(get_config('theme_remui', 'testimonialtext' . ($index))) == '') {
            return false;
        }
        return true;
    }

    /**
     * Create testimonial from older configs
     * @return int Testimonial section id
     */
    private function create_testimonial_from_older_configs() {
        global $OUTPUT;
        $section = $this->section_configuration('testimonial');
        $testimonial = (int)get_config('theme_remui', 'enablefrontpageaboutus');
        $count = (int)get_config('theme_remui', 'testimonialcount');
        if ($testimonial == 0 || $count == 0) {
            return false;
        }
        $defaultimage = $OUTPUT->image_url('u/f2')->out();
        $testimonials = [];
        $defaulttestimonial = $section['configdata']['testimonial'][0];
        $section['configdata']['title']['text'] = get_config('theme_remui', 'frontpageaboutusheading');
        $section['configdata']['description']['text'] = strip_tags(get_config('theme_remui', 'frontpageaboutustext'));
        $section['configdata']['testimonials'] = $count;
        $counter = 0;
        for ($i = 0; $i < $count; $i++) {
            if ($this->verify_testimonial_settings($i + 1) !== true) {
                continue;
            }
            $testimonial = $defaulttestimonial;
            unset($testimonial['status']);
            $testimonial['counter'] = $counter++;
            $testimonial['imageurl'] = $defaultimage;
            $testimonial['name']['text'] = get_config('theme_remui', 'testimonialname' . ($i + 1));
            $testimonial['designation']['text'] = get_config('theme_remui', 'testimonialdesignation' . ($i + 1));
            $testimonial['testimonial']['text'] = strip_tags(get_config('theme_remui', 'testimonialtext' . ($i + 1)));
            $file = $this->create_file_from_settings('testimonialimage' . ($i + 1), 'section_testimonial');
            $testimonial = $this->add_image_to_object($testimonial, $file);
            $testimonials[] = $testimonial;
        }
        if (empty($testimonials)) {
            return false;
        }
        $testimonials[0]['status'] = 'active';
        $section['configdata']['testimonial'] = $testimonials;
        return $this->create_section($section);
    }

    /**
     * Check whether user has older frontapge settings
     * @return boolean true if older settings present
     */
    public function has_settings() {
        $testimonial = (int)get_config('theme_remui', 'enablefrontpageaboutus');
        $count = (int)get_config('theme_remui', 'testimonialcount');
        if ($testimonial != 0 && $count > 0) {
            return true;
        }
        $aboutus = get_config('theme_remui', 'frontpageblockdisplay');
        if ($aboutus != 1) {
            return true;
        }
        $frontpageimagecontent = get_config('theme_remui', 'frontpageimagecontent');
        $contenttype = get_config('theme_remui', 'contenttype');
        if ($frontpageimagecontent == 0) {
            if ($contenttype == 1 && get_config('theme_remui', 'staticimage') != '') {
                return true;
            }
        } else {
            $slidercount = get_config('theme_remui', 'slidercount');
            for ($i = 1; $i <= $slidercount; $i++) {
                if (get_config('theme_remui', 'slideimage' . $i) == '') {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Create sections from older configs to preserve page view.
     */
    public function create_sections_from_older_configs() {
        global $DB;

        // Clean if there is previous sections.
        $this->delete_all_instances();

        $order = [];

        // Header content (Slider).
        $section = $this->create_slider_from_older_configs();
        if ($section !== false) {
            $order[] = $section;
        }

        // Body Content (About us).
        $section = $this->create_aboutus_from_older_configs();
        if ($section !== false) {
            $order[] = $section;
        }

        // About us or testimonial.
        $section = $this->create_testimonial_from_older_configs();
        if ($section !== false) {
            $order[] = $section;
        }

        set_config('sections_order', json_encode($order), 'theme_remui');
        set_config('draft_sections_order', json_encode($order), 'theme_remui');
    }
}
