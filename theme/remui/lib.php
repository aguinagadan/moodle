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
 * Theme functions.
 *
 * @package    theme_remui
 * @copyright  2016 Frédéric Massart - FMCorz.net
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


// Handle license status change on form submit.
$lcontroller = new \theme_remui\controller\license_controller();
$lcontroller->addData();

/**
 * Reset all caches
 */
function remui_clear_cache() {
    global $CFG, $PAGE;
    $link = $PAGE->url;
    $link->remove_params();
    purge_other_caches();
    remove_dir($CFG->dataroot . '/temp/theme/remui');
    theme_reset_all_caches();
    redirect($link);
}

if (isset($_POST['applysitewidecolor'])) {
    remui_clear_cache();
}


/**
 * Post process the CSS tree.
 *
 * @param string $tree The CSS tree.
 * @param theme_config $theme The theme config object.
 */
function theme_remui_css_tree_post_processor($tree, $theme) {
    $prefixer = new theme_remui\autoprefixer($tree);
    $prefixer->prefix();
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_remui_get_extra_scss($theme) {
    $content = '';
    $imageurl = $theme->setting_file_url('backgroundimage', 'backgroundimage');

    // Sets the background image, and its settings.
    if (!empty($imageurl)) {
        $content .= 'body { ';
        $content .= "background-image: url('$imageurl'); background-size: cover;";
        $content .= ' }';
    }

    // Always return the background image with the scss when we have it.
    return !empty($theme->settings->scss) ? $theme->settings->scss . ' ' . $content : $content;
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_remui_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        send_file_not_found();
    }
    // By default, theme files must be cache-able by both browsers and proxies.
    $settings = [
        'frontpageloader',
        'staticimage',
        'testimonialimage0',
        'testimonialimage1',
        'testimonialimage2',
        'slideimage0',
        'slideimage1',
        'slideimage2',
        'slideimage3',
        'slideimage4',
        'frontpageblockimage1',
        'frontpageblockimage2',
        'frontpageblockimage3',
        'frontpageblockimage4',
        'logo',
        'logomini',
        'faviconurl',
        'loginsettingpic'
    ];
    if (in_array($filearea, $settings)) {
        $theme = theme_config::load('remui');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        $itemid = (int)array_shift($args);
        $relativepath = implode('/', $args);
        $fullpath = "/{$context->id}/theme_remui/$filearea/$itemid/$relativepath";
        $fs = get_file_storage();
        if (!($file = $fs->get_file_by_hash(sha1($fullpath)))) {
            return false;
        }
        // Download MUST be forced - security!
        send_stored_file($file, 0, 0, $forcedownload, $options);
    }
    return false;
}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_remui_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/remui/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/remui/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_remui', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/remui/scss/preset/default.scss');
    }
    return $scss;
}

/**
 * Get compiled css.
 *
 * @return string compiled css
 */
function theme_remui_get_precompiled_css() {
    global $CFG;
    return file_get_contents($CFG->dirroot . '/theme/remui/style/remui-min.css');
}

/**
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 *
 * @return array
 */
function theme_remui_get_pre_scss($theme) {
    global $CFG;

    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['primary'],
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    if (!empty($theme->settings->fontsize)) {
        $scss .= '$font-size-base: ' . (1 / 100 * $theme->settings->fontsize) . "rem !default;\n";
    }

    return $scss;
}

/**
 * Get unused item id for file uploading
 *
 * @param  String  $filearea File area of file
 *
 * @return Integer           File item id
 */
function theme_remui_get_unused_itemid($filearea) {
    global $DB, $USER;

    if (isguestuser() or !isloggedin()) {
        // Guests and not-logged-in users can not be allowed to upload anything!!!!!!
        print_error('noguest');
    }

    $contextid = context_system::instance()->id;

    $fs = get_file_storage();
    $itemid = rand(1, 999999999);
    while ($files = $fs->get_area_files($contextid, 'theme_remui', $filearea, $itemid)) {
        $itemid = rand(1, 999999999);
    }

    return $itemid;
}

/**
 * Get image url of file using itemid, component and filearea
 *
 * @param  Integer $itemid    File item id
 * @param  String  $component File component
 * @param  String  $filearea  File area
 *
 * @return String             File url
 */
function get_file_img_url($itemid, $component, $filearea) {
    $context = \context_system::instance();

    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, $component, $filearea, $itemid);
    foreach ($files as $file) {
        if ($file->get_filename() != '.') {
            return moodle_url::make_pluginfile_url(
                $file->get_contextid(),
                $file->get_component(),
                $file->get_filearea(),
                $file->get_itemid(),
                $file->get_filepath(),
                $file->get_filename(),
                false)->out();
        }
    }
    return "";
}

function theme_remui_process_css($css, $theme) {
    // Set login background.
    $tag = '[[setting:login_bg]]';
    $loginbg = \theme_remui\toolbox::setting_file_url('loginsettingpic', 'loginsettingpic');
    if (empty($loginbg)) {
        $loginbg = \theme_remui\toolbox::image_url('login_bg', 'theme');
    }
    $css = str_replace($tag, $loginbg, $css);

    // Get the theme font from setting and apply it in CSS.
    if (\theme_remui\toolbox::get_setting('fontselect') === "2") {
        $fontname = ucwords(\theme_remui\toolbox::get_setting('fontname'));
    }
    if (empty($fontname)) {
        $fontname = 'Open Sans';
    }

    $css = \theme_remui\toolbox::set_font($css, $fontname);

    // Set custom CSS.
    $customcss = \theme_remui\toolbox::get_setting('customcss');
    $css .= $customcss;

    // Set primary color.
    $css = str_replace($tag, $loginbg, $css);
    $colorhex = get_config('theme_remui', 'sitecolorhex');
    if ($colorhex != "" || $colorhex != null) {
        $colorhex = '#'.$colorhex;
        $colorobj = new \theme_remui\Color($colorhex);
        $css = str_replace('#1177d1', $colorhex, $css);
        $css = str_replace('#62a8ea', $colorhex, $css);
        $css = str_replace('#3e8ef7', $colorhex, $css);
        $css = str_replace('#589ffc', '#'.$colorobj->darken(3), $css); // on hover
        $css = str_replace('#0e63ae', '#'.$colorobj->darken(3), $css);
        $css = str_replace('#55a1e8', '#'.$colorobj->darken(3), $css); // On Hover.
        $css = str_replace('#4c9ce7', '#'.$colorobj->darken(5), $css); // On Hover.
        $css = str_replace('#0d5ca2', '#'.$colorobj->darken(5), $css); // On Focus.
    }

    return $css;
}

