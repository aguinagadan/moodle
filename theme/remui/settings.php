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

/**
 * @package   theme_remui
 * @copyright 2019 WisdmLabs
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$remuisettings = [];

if ($ADMIN->fulltree) {
    $settings = new theme_remui_admin_settingspage_tabs('themesettingremui', get_string('configtitle', 'theme_remui'));
    $page = new admin_settingpage('theme_remui_general', get_string('generalsettings', 'theme_remui'));

    $name = 'theme_remui/enableannouncement';
    $title = get_string('enableannouncement', 'theme_remui');
    $description = get_string('enableannouncementdesc', 'theme_remui');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $remuisettings['enableannouncement'] = [[
        'value'  => true,
        'show' => ['announcementtext', 'announcementtype'],
    ], [
        'value'  => false,
        'hide' => ['announcementtext', 'announcementtype'],
    ]];
    // Announcment text.
    $name = 'theme_remui/announcementtext';
    $title = get_string('announcementtext', 'theme_remui');
    $description = get_string('announcementtextdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Testimonials data for about us section.
    $name = 'theme_remui/announcementtype';
    $title = get_string('announcementtype', 'theme_remui');
    $description = get_string('announcementtypedesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        1,
        array(
        'info'    => get_string('typeinfo', 'theme_remui'),
        'success' => get_string('typesuccess', 'theme_remui'),
        'warning' => get_string('typewarning', 'theme_remui'),
        'danger'  => get_string('typedanger', 'theme_remui')
        )
    );
    $page->add($setting);

    // Setting to activate the Recent Courses Block.
    $name = 'theme_remui/enablerecentcourses';
    $title = get_string('enablerecentcourses', 'theme_remui');
    $description = get_string('enablerecentcoursesdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Setting to activate the header buttons in overlay minimal view.
    $name = 'theme_remui/enableheaderbuttons';
    $title = get_string('enableheaderbuttons', 'theme_remui');
    $description = get_string('enableheaderbuttonsdesc', 'theme_remui');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Setting to merge messaging section in right sidebar.
    $name = 'theme_remui/mergemessagingsidebar';
    $title = get_string('mergemessagingsidebar', 'theme_remui');
    $description = get_string('mergemessagingsidebardesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Course per page to shown.
    $name = 'theme_remui/courseperpage';
    $title = get_string('courseperpage', 'theme_remui');
    $description = get_string('courseperpagedesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        1,
        array(
            12 => get_string('twelve', 'theme_remui'),
            8 => get_string('eight', 'theme_remui'),
            4 => get_string('four', 'theme_remui')
        )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    // Course animation to be shown on Archieve page.
    $name = 'theme_remui/courseanimation';
    $title = get_string('courseanimation', 'theme_remui');
    $description = get_string('courseanimationdesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        'none',
        array(
            'none' => get_string('none', 'theme_remui'),
            'fade' => get_string('fade', 'theme_remui'),
            'fadeslide-top' => get_string('slide-top', 'theme_remui'),
            'slide-bottom'  => get_string('slide-bottom', 'theme_remui'),
            'slide-right'   => get_string('slide-right', 'theme_remui'),
            'scale-up'      => get_string('scale-up', 'theme_remui'),
            'scale-down'    => get_string('scale-down', 'theme_remui'),
        )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Setting to enable new cards style in course archive page.
    $name = 'theme_remui/enablenewcoursecards';
    $title = get_string('enablenewcoursecards', 'theme_remui');
    $description = get_string('enablenewcoursecardsdesc', 'theme_remui');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Setting for next and previous button in activity.
    $name = 'theme_remui/activitynextpreviousbutton';
    $title = get_string('activitynextpreviousbutton', 'theme_remui');
    $description = get_string('activitynextpreviousbuttondesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        1,
        array(
            0 => get_string('disablenextprevious', 'theme_remui'),
            1 => get_string('enablenextprevious', 'theme_remui'),
            2 => get_string('enablenextpreviouswithname', 'theme_remui')
        )
    );
    $page->add($setting);

    $name = 'theme_remui/logoorsitename';
    $title = get_string('logoorsitename', 'theme_remui');
    $description = get_string('logoorsitenamedesc', 'theme_remui');
    $default = 'iconsitename';
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        $default,
        array('iconsitename' => get_string('iconsitename', 'theme_remui'), 'logo' => get_string('onlylogo', 'theme_remui'))
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $remuisettings['logoorsitename'] = [[
        'value'  => 'logo',
        'show' => ['logo', 'logomini'],
        'hide' => ['siteicon']
    ], [
        'value'  => 'iconsitename',
        'show' => ['siteicon'],
        'hide' => ['logo', 'logomini']
    ]];

    // Logo file setting.
    $name = 'theme_remui/logo';
    $title = get_string('logo', 'theme_remui');
    $description = get_string('logodesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'logo',
        0,
        array( 'subdirs' => 0, 'accepted_types' => 'web_image')
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // LogoMini file setting.
    $name = 'theme_remui/logomini';
    $title = get_string('logomini', 'theme_remui');
    $description = get_string('logominidesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'logomini',
        0,
        array('subdirs' => 0, 'accepted_types' => 'web_image' )
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Site icon setting.
    $name = 'theme_remui/siteicon';
    $title = get_string('siteicon', 'theme_remui');
    $description = get_string('siteicondesc', 'theme_remui');
    $default = 'graduation-cap';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Custom favicon temp.
    $name = 'theme_remui/faviconurl';
    $title = get_string('favicon', 'theme_remui');
    $description = get_string('favicondesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'faviconurl',
        0,
        array('subdirs' => 0, 'accepted_types' => 'web_image')
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Font Selector.
    $name = 'theme_remui/fontselect';
    $title = get_string('fontselect', 'theme_remui');
    $description = get_string('fontselectdesc', 'theme_remui');
    $default = 1;
    $choices = array(
        1 => get_string('fonttypestandard', 'theme_remui'),
        2 => get_string('fonttypegoogle', 'theme_remui'),
    );
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $remuisettings['fontselect'] = [[
        'value'  => '1',
        'hide' => ['fontname']
    ], [
        'value'  => '2',
        'show' => ['fontname']
    ]];

    // Heading font name.
    $name = 'theme_remui/fontname';
    $title = get_string('fontname', 'theme_remui');
    $description = get_string('fontnamedesc', 'theme_remui');
    $default = 'Roboto';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Custom CSS file.
    $name = 'theme_remui/customcss';
    $title = get_string('customcss', 'theme_remui');
    $description = get_string('customcssdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('remui_clear_cache');
    $page->add($setting);

    // Google analytics block.
    $name = 'theme_remui/googleanalytics';
    $title = get_string('googleanalytics', 'theme_remui');
    $description = get_string('googleanalyticsdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Setting for enabling course stats visibility in course page.
    $name = 'theme_remui/enablecoursestats';
    $title = get_string('enablecoursestats', 'theme_remui');
    $description = get_string('enablecoursestatsdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Dictionary.
    $name = 'theme_remui/enabledictionary';
    $title = get_string('enabledictionary', 'theme_remui');
    $description = get_string('enabledictionarydesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Setting for displaying edit on / off button addionally in course header.
    $name = 'theme_remui/courseeditbutton';
    $title = get_string('courseeditbuttonsetting', 'theme_remui', null, true);
    $description = get_string('courseeditbuttonsetting_desc', 'theme_remui', null, true);
    $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    
    // Usage tracking GDPR setting
    $name = 'theme_remui/enableusagetracking';
    $title = get_string('enableusagetracking', 'theme_remui');
    $description = get_string('enableusagetrackingdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Must add the page after definiting all the settings!
    $settings->add($page);

    // Remui block settings.
    $pluginman = core_plugin_manager::instance();
    if (array_key_exists("remuiblck", $pluginman->get_installed_plugins('block'))) {
        if (class_exists('block_remuiblck_settings')) {
            \block_remuiblck_settings::add_settings($settings);
        } else {
            // Dashboard settings.
            $page = new admin_settingpage('theme_remui_dashboard', get_string('dashboardsetting', 'theme_remui'));
            $page->add(new admin_setting_description('theme_remui_olddashboard', '', get_string('olddashboard', 'theme_remui')));
            $settings->add($page);
        }
    }

    // Homepage settings.
    $page = new admin_settingpage('theme_remui_frontpage', get_string('homepagesettings', 'theme_remui'));

    $pluginman = core_plugin_manager::instance();
    if (array_key_exists("remuihomepage", $pluginman->get_installed_plugins('local')) &&
    class_exists('local_remuihomepage_plugin')) {
        $homepage = new local_remuihomepage_plugin();
    } else {
        $homepage = false;
    }
    $activehomepage = get_config('theme_remui', 'frontpagechooser');
    if ($homepage) {
        $options = array(
            0 => get_string('frontpagedesignold', 'theme_remui'),
            1 => get_string('pluginname', $homepage->get_component())
        );
        $page->add(new admin_setting_heading(
            'theme_remui_frontpagedesign',
            get_string('frontpagedesign', 'theme_remui'),
            format_text(get_string('frontpagedesigndesc', 'theme_remui'), FORMAT_MARKDOWN)
        ));
        $name = 'theme_remui/frontpagechooser';
        $title = get_string('frontpagechooser', 'theme_remui');
        $description = get_string('frontpagechooserdesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            0,
            $options
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    } else {
        if ($activehomepage == 1) {
            set_config('frontpagechooser', 0, 'theme_remui');
        }
    }
    if ($homepage && $activehomepage == 1) {
        $homepage->admin_settings($page);
    } else {
        if (class_exists('admin_setting_heading')) {
            $page->add(new admin_setting_heading(
                'theme_remui_upsection',
                get_string('frontpageimagecontent', 'theme_remui'),
                format_text(get_string('frontpageimagecontentdesc', 'theme_remui'), FORMAT_MARKDOWN)
            ));
        }
        $name = 'theme_remui/frontpageimagecontent';
        $title = get_string('frontpageimagecontentstyle', 'theme_remui');
        $description = get_string('frontpageimagecontentstyledesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            array(
                0 => get_string('staticcontent', 'theme_remui'),
                1 => get_string('slidercontent', 'theme_remui'),
            )
        );
        $page->add($setting);

        $name = 'theme_remui/contenttype';
        $title = get_string('contenttype', 'theme_remui');
        $description = get_string('contentdesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            0,
            array(
            0 => get_string('videourl', 'theme_remui'),
            1 => get_string('image', 'theme_remui'),
            )
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/video';
        $title = get_string('video', 'theme_remui');
        $description = get_string('videodesc', 'theme_remui');
        $default = 'https://www.youtube.com/embed/wop3FMhoLGs';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/addtext';
        $title = get_string('addtext', 'theme_remui');
        $description = get_string('addtextdesc', 'theme_remui');
        $default = get_string('defaultaddtext', 'theme_remui');
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/staticimage';
        $title = get_string('uploadimage', 'theme_remui');
        $description = get_string('uploadimagedesc', 'theme_remui');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'staticimage', 0, array(
            'subdirs' => 0, 'accepted_types' => 'web_image'
        ));
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/slideinterval';
        $title = get_string('slideinterval', 'theme_remui');
        $description = get_string('slideintervaldesc', 'theme_remui');
        $default = 5000;
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/sliderautoplay';
        $title = get_string('sliderautoplay', 'theme_remui');
        $description = get_string('sliderautoplaydesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            array(
                1 => get_string('true', 'theme_remui'),
                2 => get_string('false', 'theme_remui'),
            )
        );
        $page->add($setting);

        $name = 'theme_remui/slidercount';
        $title = get_string('slidercount', 'theme_remui');
        $description = get_string('slidercountdesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            array(
                1 => get_string('one', 'theme_remui'),
                2 => get_string('two', 'theme_remui'),
                3 => get_string('three', 'theme_remui'),
                4 => get_string('four', 'theme_remui'),
                5 => get_string('five', 'theme_remui'),
            )
        );
        $page->add($setting);
        $remuisettings['slidercount'] = [];
        for ($slidecounts = 1; $slidecounts <= 5; $slidecounts = $slidecounts + 1) {
            $slidervisibility = [
                'value' => $slidecounts,
                'show' => [],
                'hide' => []
            ];
            for ($i = 1; $i <= $slidecounts; $i++) {
                $slidervisibility['show'][] = 'slideimage' . $i;
                $slidervisibility['show'][] = 'slidertext' . $i;
                $slidervisibility['show'][] = 'sliderbuttontext' . $i;
                $slidervisibility['show'][] = 'sliderurl' . $i;
            }
            for ($i = $slidecounts + 1; $i <= 5; $i++) {
                $slidervisibility['hide'][] = 'slideimage' . $i;
                $slidervisibility['hide'][] = 'slidertext' . $i;
                $slidervisibility['hide'][] = 'sliderbuttontext' . $i;
                $slidervisibility['hide'][] = 'sliderurl' . $i;
            }
            $remuisettings['slidercount'][] = $slidervisibility;
            $name = 'theme_remui/slideimage'.$slidecounts;
            $title = get_string('slideimage', 'theme_remui');

            $description = get_string('slideimagedesc', 'theme_remui');
            $setting = new admin_setting_configstoredfile($name, $title, $description, 'slideimage'.$slidecounts, 0, array(
                'subdirs' => 0, 'accepted_types' => 'web_image'
            ));
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/slidertext'.$slidecounts;
            $title = get_string('slidertext', 'theme_remui');
            $description = get_string('slidertextdesc', 'theme_remui');
            $default = get_string('defaultslidertext', 'theme_remui');
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/sliderbuttontext'.$slidecounts;
            $title = get_string('sliderbuttontext', 'theme_remui');
            $description = get_string('sliderbuttontextdesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/sliderurl'.$slidecounts;
            $title = get_string('sliderurl', 'theme_remui');
            $description = get_string('sliderurldesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
        }
        $remuisettings['contenttype'] = [[
            'value' => 0,
            'show' => ['video'],
            'hide' => ['addtext', 'staticimage']
        ], [
            'value' => 1,
            'show' => ['addtext', 'staticimage'],
            'hide' => ['video']
        ]];
        $remuisettings['frontpageimagecontent'] = [[
            'value' => 0,
            'show' => ['contenttype'],
            'hide' => [
                'slideinterval',
                'sliderautoplay',
                'slidercount'
            ]
        ], [
            'value' => 1,
            'show' => [
                'slideinterval',
                'sliderautoplay',
                'slidercount'
            ],
            'hide' => ['contenttype']
        ]];
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Marketing blocks.
        $page->add(new admin_setting_heading(
            'theme_remui_blocksection',
            get_string('frontpageblocks', 'theme_remui'),
            format_text(get_string('frontpageblocksdesc', 'theme_remui'), FORMAT_MARKDOWN)
        ));

        // Show the About Us on Home Page Setting.
        $name = 'theme_remui/frontpageblockdisplay';
        $title = get_string('frontpageblockdisplay', 'theme_remui');
        $description = get_string('frontpageblockdisplaydesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            array(
                    1 => get_string('donotshowaboutus', 'theme_remui'),
                    2 => get_string('showaboutusinrow', 'theme_remui'),
                    3 => get_string('showaboutusingridblock', 'theme_remui'),
            )
        );


        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Marketing spot section heading.
        $name = 'theme_remui/frontpageblockheading';
        $title = get_string('frontpageaboutus', 'theme_remui');
        $description = get_string('frontpageaboutustitledesc', 'theme_remui');
        $default = 'About Us';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Description for above.
        $name = 'theme_remui/frontpageblockdesc';
        $title = get_string('frontpageaboutusbody', 'theme_remui');
        $description = get_string('frontpageaboutusbodydesc', 'theme_remui');
        $default = 'Holisticly harness just in time technologies via corporate scenarios.';
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enablesectionbutton';
        $title = get_string('enablesectionbutton', 'theme_remui');
        $description = get_string('enablesectionbuttondesc', 'theme_remui');
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $remuisettings['frontpageblockdisplay'] = [];
        $blockicons = ['flag', 'globe', 'cog', 'users'];
        $targets = [
            'frontpageblockheading',
            'frontpageblockdesc',
            'enablesectionbutton'
        ];
        for ($blockcount = 1; $blockcount <= 4; $blockcount++) {
            $targets = array_merge($targets, [
                'frontpageblocksection' . $blockcount,
                'sectionbuttontext' . $blockcount,
                'frontpageblockdescriptionsection' . $blockcount,
                'frontpageblockiconsection' . $blockcount,
                'frontpageblockimage' . $blockcount
            ]);

            /*block section*/
            $name = 'theme_remui/frontpageblocksection' . $blockcount;
            $title = get_string('frontpageblocksection' . $blockcount, 'theme_remui');
            $description = get_string('frontpageblocksectiondesc', 'theme_remui');
            $default = 'LOREM IPSUM';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/frontpageblockdescriptionsection' . $blockcount;
            $title = get_string('frontpageblockdescriptionsection' . $blockcount, 'theme_remui');
            $description = get_string('frontpageblockdescriptionsectiondesc', 'theme_remui');
            $default = get_string('defaultdescriptionsection', 'theme_remui');
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/frontpageblockiconsection' . $blockcount;
            $title = get_string('frontpageblockiconsection' . $blockcount, 'theme_remui');
            $description = get_string('frontpageblockiconsectiondesc', 'theme_remui');
            $default = $blockicons[$blockcount - 1];
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/sectionbuttontext' . $blockcount;
            $title = get_string('sectionbuttontext' . $blockcount, 'theme_remui');
            $description = get_string('sectionbuttontextdesc', 'theme_remui');
            $default = 'Read More';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/sectionbuttonlink' . $blockcount;
            $title = get_string('sectionbuttonlink' . $blockcount, 'theme_remui');
            $description = get_string('sectionbuttonlinkdesc', 'theme_remui');
            $default = '#';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/frontpageblockimage' . $blockcount;
            $title = get_string('frontpageblockimage', 'theme_remui');
            $description = get_string('frontpageblockimagedesc', 'theme_remui');
            $setting = new admin_setting_configstoredfile($name, $title, $description, 'frontpageblockimage' . $blockcount, 0, array(
                'subdirs' => 0, 'accepted_types' => 'web_image'
            ));
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
        }
        $visibility = [
            'value' => 1,
            'hide' => $targets
        ];
        $remuisettings['frontpageblockdisplay'][] = $visibility;
        $visibility['value'] = 2;
        $visibility['show'] = $visibility['hide'];
        unset($visibility['hide']);
        $remuisettings['frontpageblockdisplay'][] = $visibility;
        $visibility['value'] = 3;
        $remuisettings['frontpageblockdisplay'][] = $visibility;

        $remuisettings['enablesectionbutton'] = [[
            'value' => true,
            'show' => [
                'sectionbuttonlink1',
                'sectionbuttonlink2',
                'sectionbuttonlink3',
                'sectionbuttonlink4'
            ]
        ], [
            'value' => false,
            'hide' => [
                'sectionbuttonlink1',
                'sectionbuttonlink2',
                'sectionbuttonlink3',
                'sectionbuttonlink4'
            ]
        ]];

        // Frontpage Aboutus settings.
        $page->add(new admin_setting_heading(
            'theme_remui_frontpage_aboutus',
            get_string('frontpageaboutus', 'theme_remui'),
            format_text(get_string('frontpageaboutusdesc', 'theme_remui'), FORMAT_MARKDOWN)
        ));


        $name = 'theme_remui/enablefrontpageaboutus';
        $title = get_string('enablefrontpageaboutus', 'theme_remui');
        $description = get_string('enablefrontpageaboutusdesc', 'theme_remui');
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Heading text for about us.
        $name = 'theme_remui/frontpageaboutusheading';
        $title = get_string('frontpageaboutusheading', 'theme_remui');
        $description = get_string('frontpageaboutusheadingdesc', 'theme_remui');
        $default = "About us";
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Text for about us.
        $name = 'theme_remui/frontpageaboutustext';
        $title = get_string('frontpageaboutustext', 'theme_remui');
        $description = get_string('frontpageaboutustextdesc', 'theme_remui');
        $default = get_string('frontpageaboutusdefault', 'theme_remui');
        ;
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Testimonials data for about us section.
        $name = 'theme_remui/testimonialcount';
        $title = get_string('testimonialcount', 'theme_remui');
        $description = get_string('testimonialcountdesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            array(
                1 => get_string('one', 'theme_remui'),
                2 => get_string('two', 'theme_remui'),
                3 => get_string('three', 'theme_remui')
                )
        );
        $page->add($setting);

        $remuisettings['testimonialcount'] = [];
        for ($testimonialcount = 1; $testimonialcount <= 3; $testimonialcount++) {
            $testivisibility = [
                'value' => $testimonialcount,
                'show' => [],
                'hide' => []
            ];
            for ($i = 1; $i <= $testimonialcount; $i++) {
                $testivisibility['show'][] = 'testimonialimage' . $i;
                $testivisibility['show'][] = 'testimonialname' . $i;
                $testivisibility['show'][] = 'testimonialdesignation' . $i;
                $testivisibility['show'][] = 'testimonialtext' . $i;
            }
            for (; $i <= 3; $i++) {
                $testivisibility['hide'][] = 'testimonialimage' . $i;
                $testivisibility['hide'][] = 'testimonialname' . $i;
                $testivisibility['hide'][] = 'testimonialdesignation' . $i;
                $testivisibility['hide'][] = 'testimonialtext' . $i;
            }
            $remuisettings['testimonialcount'][] = $testivisibility;
            // Image.
            $name = 'theme_remui/testimonialimage'.$testimonialcount;
            $title = get_string('testimonialimage', 'theme_remui');
            $description = get_string('testimonialimagedesc', 'theme_remui');
            $setting = new admin_setting_configstoredfile(
                $name,
                $title,
                $description,
                'testimonialimage'.$testimonialcount,
                0,
                array('subdirs' => 0, 'accepted_types' => 'web_image')
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            // Name.
            $name = 'theme_remui/testimonialname'.$testimonialcount;
            $title = get_string('testimonialname', 'theme_remui');
            $description = get_string('testimonialnamedesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            // Post.
            $name = 'theme_remui/testimonialdesignation'.$testimonialcount;
            $title = get_string('testimonialdesignation', 'theme_remui');
            $description = get_string('testimonialdesignationdesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            // Description.
            $name = 'theme_remui/testimonialtext'.$testimonialcount;
            $title = get_string('testimonialtext', 'theme_remui');
            $description = get_string('testimonialtextdesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
        }
        $remuisettings['enablefrontpageaboutus'] = [[
            'value' => true,
            'show' => [
                'frontpageaboutusheading',
                'frontpageaboutustext',
                'testimonialcount'
            ]
        ], [
            'value' => false,
            'hide' => [
                'frontpageaboutusheading',
                'frontpageaboutustext',
                'testimonialcount'
            ]
        ]];
    }

    $settings->add($page);

    // Footer Settings.
    $page = new admin_settingpage('theme_remui_footer', get_string('footersettings', 'theme_remui'));
    // Social media settings.
    $page->add(new admin_setting_heading(
        'theme_remui_socialmedia',
        get_string('socialmedia', 'theme_remui'),
        format_text(get_string('socialmediadesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    // Facebook.
    $name = 'theme_remui/facebooksetting';
    $title = get_string('facebooksetting', 'theme_remui');
    $description = get_string('facebooksettingdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Twitter.
    $name = 'theme_remui/twittersetting';
    $title = get_string('twittersetting', 'theme_remui');
    $description = get_string('twittersettingdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Linkedin.
    $name = 'theme_remui/linkedinsetting';
    $title = get_string('linkedinsetting', 'theme_remui');
    $description = get_string('linkedinsettingdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Gplus.
    $name = 'theme_remui/gplussetting';
    $title = get_string('gplussetting', 'theme_remui');
    $description = get_string('gplussettingdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Youtube.
    $name = 'theme_remui/youtubesetting';
    $title = get_string('youtubesetting', 'theme_remui');
    $description = get_string('youtubesettingdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Instagram.
    $name = 'theme_remui/instagramsetting';
    $title = get_string('instagramsetting', 'theme_remui');
    $description = get_string('instagramsettingdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Pinterest.
    $name = 'theme_remui/pinterestsetting';
    $title = get_string('pinterestsetting', 'theme_remui');
    $description = get_string('pinterestsettingdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Quora.
    $name = 'theme_remui/quorasetting';
    $title = get_string('quorasetting', 'theme_remui');
    $description = get_string('quorasettingdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    // Footer Settings.

    // Footer Column 1.
    $page->add(new admin_setting_heading(
        'theme_remui_footercolumn1',
        get_string('footercolumn1heading', 'theme_remui'),
        format_text(get_string('footercolumn1headingdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/footercolumn1title';
    $title = get_string('footercolumn1title', 'theme_remui');
    $description = get_string('footercolumn1titledesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, null);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/footercolumn1customhtml';
    $title = get_string('footercolumn1customhtml', 'theme_remui');
    $description = get_string('footercolumn1customhtmldesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    // Footer Column 2.
    $page->add(new admin_setting_heading(
        'theme_remui_footercolumn2',
        get_string('footercolumn2heading', 'theme_remui'),
        format_text(get_string('footercolumn2headingdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/footercolumn2title';
    $title = get_string('footercolumn2title', 'theme_remui');
    $description = get_string('footercolumn2titledesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, null);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/footercolumn2customhtml';
    $title = get_string('footercolumn2customhtml', 'theme_remui');
    $description = get_string('footercolumn2customhtmldesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer Column 3.
    $page->add(new admin_setting_heading(
        'theme_remui_footercolumn3',
        get_string('footercolumn3heading', 'theme_remui'),
        format_text(get_string('footercolumn3headingdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/footercolumn3title';
    $title = get_string('footercolumn3title', 'theme_remui');
    $description = get_string('footercolumn3titledesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, null);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/footercolumn3customhtml';
    $title = get_string('footercolumn3customhtml', 'theme_remui');
    $description = get_string('footercolumn3customhtmldesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    // Footer Bottom-Right Section.
    $page->add(new admin_setting_heading(
        'theme_remui_footerbottom',
        get_string('footerbottomheading', 'theme_remui'),
        format_text(get_string('footerbottomdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/footerbottomtext';
    $title = get_string('footerbottomtext', 'theme_remui');
    $description = get_string('footerbottomtextdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/footerbottomlink';
    $title = get_string('footerbottomlink', 'theme_remui');
    $description = get_string('footerbottomlinkdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/poweredbyedwiser';
    $title = get_string('poweredbyedwiser', 'theme_remui');
    $description = get_string('poweredbyedwiserdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // Login Page Settings.
    $page = new admin_settingpage('theme_remui_login', get_string('loginsettings', 'theme_remui'));
    $page->add(new admin_setting_heading(
        'theme_remui_login',
        get_string('loginsettings', 'theme_remui'),
        format_text('', FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/navlogin_popup';
    $title = get_string('navlogin_popup', 'theme_remui');
    $description = get_string('navlogin_popupdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/loginsettingpic';
    $title = get_string('loginsettingpic', 'theme_remui');
    $description = get_string('loginsettingpicdesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginsettingpic', 0, array(
        'subdirs' => 0, 'accepted_types' => 'web_image'
    ));
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/signuptextcolor';
    $title = get_string('signuptextcolor', 'theme_remui');
    $description = get_string('signuptextcolordesc', 'theme_remui');
    $default = '#FFFFFF';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Brand Logo Position Setting.
    $name = 'theme_remui/brandlogopos';
    $title = get_string('brandlogopos', 'theme_remui');
    $description = get_string('brandlogoposdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Text with Brand Logo.
    $name = 'theme_remui/brandlogotext';
    $title = get_string('brandlogotext', 'theme_remui');
    $description = get_string('brandlogotextdesc', 'theme_remui');
    $default = ""; // Default string will be blank.
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);

}
if (optional_param('section', '', PARAM_TEXT) == 'themesettingremui') {
    global $PAGE;
    $PAGE->requires->data_for_js('remuisettings', $remuisettings);
    $PAGE->requires->js(new moodle_url('/theme/remui/settings.js'));
    $PAGE->requires->js_call_amd('theme_remui/settings', 'init');
}

