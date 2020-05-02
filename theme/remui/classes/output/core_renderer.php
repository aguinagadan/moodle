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

namespace theme_remui\output;

use moodle_url;
use moodle_page;
use html_writer;
use pix_icon;
use context_course;
use core_text;
use stdClass;
use action_menu;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_remui
 * @copyright  2012 Bas Brands, www.basbrands.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class core_renderer extends \core_renderer {

    protected $themeconfig;

    /**
     * We don't like these...
     *
     */
    public function edit_button(moodle_url $url) {
        if (get_config('theme_remui', 'courseeditbutton') == '1') {
            return \core_renderer::edit_button($url);
        }
        return '';
    }

    /**
     * Constructor
     *
     * @param moodle_page $page the page we are doing output for.
     * @param string $target one of rendering target constants
     */
    public function __construct(moodle_page $page, $target) {
        parent::__construct($page, $target);
        $this->themeconfig = array(\theme_config::load('remui'));
    }

    public function get_theme_config() {
        return $this->themeconfig;
    }

    /**
     * Renders an action menu component.
     *
     * @param action_menu $menu
     * @return string HTML
     */
    public function render_action_menu(action_menu $menu) {
        if ($menu->is_empty()) {
            return '';
        }
        $context = $menu->export_for_template($this);
        if (get_config('theme_remui', 'courseeditbutton') == '1') {
            $context->courseeditbutton = true;
        }

        return $this->render_from_template('core/action_menu', $context);
    }

    /**
     * Show license or update notice
     *
     * @return HTML for license notice.
     */
    public function show_license_notice() {
        // Get license data from license controller.
        $lcontroller = new \theme_remui\controller\license_controller();
        $getlidatafromdb = $lcontroller->getDataFromDb();
        if (isloggedin() && !isguestuser()) {
            $content = '';
            $classes = ['alert', 'alert-danger', 'text-center', 'text-white'];
            if ('available' != $getlidatafromdb) {
                $classes[] = 'bg-danger';
                if (is_siteadmin()) {
                    $content .= '<strong>'.get_string('licensenotactiveadmin', 'theme_remui').'</strong>';
                } else {
                    $content .= get_string('licensenotactive', 'theme_remui');
                }
            } else if ('available' == $getlidatafromdb) {
                $licensekeyactivate = \theme_remui\toolbox::get_setting('licensekeyactivate');

                if (isset($licensekeyactivate) && !empty($licensekeyactivate)) {
                    $classes[] = 'bg-success';
                    $content .= get_string('licensekeyactivated', 'theme_remui');
                } else {
                    // Show update notice if license is active and update is available
                    $available  = \theme_remui\utility::check_remui_update();
                    if (is_siteadmin() && $available == 'available') {
                        $classes[] = 'update-nag bg-info';
                        $content .= get_string('newupdatemessage', 'theme_remui');
                    }
                }
            }
            if ($content != '') {
                $content .= '<button type="button" class="close text-white" data-dismiss="alert" aria-hidden="true">×</button>';
                return html_writer::tag('div', $content, array(
                    'class' => implode(' ', $classes),
                    'style' => 'position: fixed; z-index: 9999; width: 100%; top: 60px; left: 0; right: 0; border: 0; border-radius: 0;'
                ));
            }
        }
        return '';
    }

    /**
     * The standard HTML that should be output just before the <footer> tag.
     * Designed to be called in theme layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_after_main_region_html() {
        global $CFG;
        $output = '';
        if ($this->page->pagelayout !== 'embedded' && !empty($CFG->additionalhtmlbottomofbody)) {
            $output .= "\n".$CFG->additionalhtmlbottomofbody;
        }

        // Merge messaging panel into right sidebar or not.
        $mergemessagingsidebar = \theme_remui\toolbox::get_setting('mergemessagingsidebar');

        // Give subsystems an opportunity to inject extra html content. The callback
        // must always return a string containing valid html.
        foreach (\core_component::get_core_subsystems() as $name => $path) {
            if ($path) {
                // Remui, because messages are in sidebar, so skip here.
                if ($mergemessagingsidebar  && $name == 'message') {
                    continue;
                }
                $output .= component_callback($name, 'standard_after_main_region_html', [], '');
            }
        }

        // Give plugins an opportunity to inject extra html content. The callback
        // must always return a string containing valid html.
        $pluginswithfunction = get_plugins_with_function('standard_after_main_region_html', 'lib.php');
        foreach ($pluginswithfunction as $plugins) {
            foreach ($plugins as $function) {
                $output .= $function();
            }
        }

        return $output;
    }

    /**
     * Whether we should display the logo in the navbar.
     *
     * We will when there are no main logos, and we have compact logo.
     *
     * @return bool
     */
    public function should_display_logo() {
        global $SITE;

        $logoorsitename = \theme_remui\toolbox::get_setting('logoorsitename');
        $context = array('islogo' => false, 'issitename' => false, 'isiconsitename' => false);
        $checklogo = \theme_remui\toolbox::setting_file_url('logo', 'logo');
        $checklogomini = \theme_remui\toolbox::setting_file_url('logomini', 'logomini');

        if (!empty($checklogo)) {
            $logo = $checklogo;
        } else {
            $logo = \theme_remui\toolbox::image_url('logo', 'theme');
        }

        if (!empty($checklogomini)) {
            $logomini = $checklogomini;
        } else {
            $logomini = \theme_remui\toolbox::image_url('logomini', 'theme');
        }

        if ($logoorsitename == 'logo') {
            $context['islogo'] = true;
            $context['logourl'] = $logo;
            $context['logominiurl'] = $logomini;
        } else {
            $context['isiconsitename'] = true;
            $context['siteicon'] = \theme_remui\toolbox::get_setting('siteicon');
            $context['sitename'] = format_string($SITE->shortname);
        }
        return $context;
    }

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $CFG, $SITE;
        $context = $form->export_for_template($this);

        // Override because rendering is not supported in template yet.
        if ($CFG->rememberusername == 0) {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabledonlysession');
        } else {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        }
        $context->errorformatted = $this->error_text($context->error);
        $url = $this->get_logo_url();
        if ($url) {
            $url = $url->out(false);
        }
        $context->logourl = $url;
        $context->sitename = format_string($SITE->fullname, true,
            ['context' => \context_course::instance(SITEID), "escape" => false]);
        $context->siteicon = \theme_remui\toolbox::get_setting('siteicon');
        $context->loginpage_context = $this->should_display_logo();
        $context->loginsocial_context = \theme_remui\utility::get_footer_data(1);
        $context->logopos = get_config('theme_remui', 'brandlogopos');
        $sitetext = get_config('theme_remui', 'brandlogotext');
        if ($sitetext != '') {
            $context->sitedesc = $sitetext;
        }

        return $this->render_from_template('core/loginform', $context);
    }

    /**
     * Render the login signup form into a nice template for the theme.
     *
     * @param mform $form
     * @return string
     */
    public function render_login_signup_form($form) {
        global $SITE;

        $context = $form->export_for_template($this);
        $url = $this->get_logo_url();
        if ($url) {
            $url = $url->out(false);
        }
        $context['logourl'] = $url;
        $context['sitename'] = format_string(
            $SITE->fullname,
            true,
            ['context' => context_course::instance(SITEID), "escape" => false]);

        // Modify form html.
        $context['formhtml'] = str_replace(
            array('form-inline', 'col-md-9', 'col-md-3', 'btn-primary', 'btn-secondary'),
            array('', 'col-md-11', 'col-md-1 p-0', 'btn-primary btn-block', 'btn-default btn-block'),
            $context['formhtml']
        );
        $context['loginpage_context'] = $this->should_display_logo();
        $context['logopos'] = get_config('theme_remui', 'brandlogopos');
        $sitetext = get_config('theme_remui', 'brandlogotext');
        if ($sitetext != '') {
            $context['sitedesc'] = $sitetext;
        }
        return $this->render_from_template('core/signup_form_layout', $context);
    }

    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');
        require_once($CFG->dirroot . '/lib/moodlelib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = array('class' => 'usermenu nav-item dropdown user-menu login-menu');
        if (!$withlinks) {
            $usermenuclasses = array('class' => ' nav-item dropdown user-menu login-menu withoutlinks');
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $logindropdown = \theme_remui\toolbox::get_setting('navlogin_popup');
        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        $forgotpasswordurl = new moodle_url('/login/forgot_password.php');
        $loginurldatatoggle = '';
        if ($logindropdown) {
            $loginurl = '#';
            $loginurldatatoggle = 'data-toggle="dropdown"';
        }

        $logintoken = \core\session\manager::get_login_token();
        $tokenhtml = '<div class="form-group">
                <input type="hidden" name="logintoken" value="'.$logintoken.'">
            </div>';

        // Sign in popup.
        $signinformhtml = '<div class="dropdown-menu  dropdown-menu-right loginddown p-15" role="menu"
                    data-region="popover-region-container">
                    <form class="mb-0" action="'.get_login_url().'" method="post" id="login">
                        <div class="form-group">
                            <label for="username" class="sr-only">'.get_string('username').'</label>
                            <input type="text" class="form-control" id="username" name="username"
                            placeholder="'.get_string('username').'">
                        </div>

                        <div class="form-group">
                            <label for="password" class="sr-only">'.get_string('password').'</label>
                            <input type="password" name="password" id="password" value=""
                            class="form-control"placeholder='.get_string('password').'>
                        </div>
                        '.$tokenhtml.'
                        <div class="form-group clearfix">
                            <div class="checkbox-custom checkbox-inline checkbox-primary float-left rememberpass">
                                <input type="checkbox" id="rememberusername" name="rememberusername" value="1" />
                                <label for="rememberusername">'.get_string('rememberusername', 'admin').'</label>
                            </div>
                            <a class="float-right" href="'.$forgotpasswordurl.'">'.get_string("forgotpassword", "theme_remui").'</a>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" id="loginbtn">'.get_string('login').'</button>
                    </form>';

        $authsequence = get_enabled_auth_plugins(true); // Get all auths, in sequence.

        $potentialidps = array();
        foreach ($authsequence as $authname) {
            $authplugin = get_auth_plugin($authname);
            $potentialidps = array_merge($potentialidps, $authplugin->loginpage_idp_list($this->page->url->out(false)));
        }
        if (!empty($potentialidps)) {
            $signinformhtml .= '<div class="potentialidp my-10 text-center">';
            $signinformhtml .= '<label class="w-full">Log in using your account on</label>';
            $signinformhtml .= '<div class="button-group ">';
            foreach ($potentialidps as $idp) {
                $signinformhtml .= '<a class="btn btn-icon" href="' . $idp['url']->out()
                . '" title="' . s($idp['name']) . ' Login">';
                if (!empty($idp['iconurl'])) {
                    $signinformhtml .= '<img src="' . s($idp['iconurl']) . '" width="24" height="24" />';
                }
                $signinformhtml .= '</a>';
            }
            $signinformhtml .= '</div>';
            $signinformhtml .= '</div>';
        }
        $signinformhtml .= '</div>';

        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            $returnstr = '';
            if (!$loginpage) {
                $returnstr = '<a href="'.$loginurl.'" class="nav-link" '.$loginurldatatoggle.' data-animation="scale-up">
                <i class="icon fa fa-user"></i>'.get_string('login').'</a>';

                if ($logindropdown) {
                    $returnstr  .= $signinformhtml;
                }
            }

            return html_writer::tag('div', $returnstr, $usermenuclasses);
        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $returnstr = '';
            if (!$loginpage && $withlinks) {
                $returnstr = '<a href="'.$loginurl.'" class="nav-link" '.$loginurldatatoggle.' data-animation="scale-up">
                <i class="icon fa fa-user"></i>'.get_string('login').'</a>';

                if ($logindropdown) {
                    $returnstr  .= $signinformhtml;
                }
            }
            return html_writer::tag('div', $returnstr, $usermenuclasses);
        }

        // Get some navigation opts.

        $opts = user_get_user_navigation_info($user, $this->page);

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = $opts->metadata['userfullname'];

        // Other user.
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usertextcontents = $opts->metadata['realuserfullname'];
            $usertextcontents .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usertextcontents .= html_writer::span(
                ' ('. $opts->metadata['rolename'] .')',
                'meta text-uppercase font-size-12 role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usertextcontents .= html_writer::span(
                $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usertextcontents .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            html_writer::span($usertextcontents, 'usertext') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider.
        $divider = '<div class="dropdown-divider" role="presentation"></div>';

        $usermenu = '';
        $usermenu .= '<a class="nav-link navbar-avatar" data-toggle="dropdown" href="#"
            aria-expanded="false" data-animation="scale-up" role="button">
            <span class="username pr-1">'.$usertextcontents.'</span>
            <span class="avatar avatar-online current">
            '.$opts->metadata['useravatar'].'
            <i style="border-color: white;"></i>
            </span>
        </a>';

        $usermenu .= '<div class="dropdown-menu  dropdown-menu-right" role="menu">';
        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {
                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $usermenu .= $divider;
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'link':
                        // Process this as a link item.
                        $pix = null;
                        if (isset($value->pix) && !empty($value->pix)) {
                            $pix = new pix_icon($value->pix, $value->title, null, array('class' => 'iconsmall'));
                        } else if (isset($value->imgsrc) && !empty($value->imgsrc)) {
                            $value->title = html_writer::img(
                                $value->imgsrc,
                                $value->title,
                                array('class' => 'iconsmall')
                            ) . $value->title;
                        }

                        $icon = $this->pix_icon($pix->pix, '', 'moodle', $pix->attributes);
                        $usermenu .= '<a class="dropdown-item" href="'.$value->url.'" role="menuitem">'.$icon.$value->title.'</a>';
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $usermenu .= $divider;
                }
            }
        }
        $usermenu .= '</div>';

        return html_writer::tag('div', $usermenu, $usermenuclasses);
    }

    /**
     * The standard tags that should be included in the <head> tag
     * including a meta description for the front page
     *
     * @return string HTML fragment.
     */
    public function standard_head_html() {
        global $SITE, $PAGE;

        $output = parent::standard_head_html();
        if ($PAGE->pagelayout == 'frontpage') {
            $summary = s(strip_tags(format_text($SITE->summary, FORMAT_HTML)));
            if (!empty($summary)) {
                $output .= "<meta name=\"description\" content=\"$summary\" />\n";
            }
        }

        // Get the theme font from setting.
        if (\theme_remui\toolbox::get_setting('fontselect') === "2") {
            $fontname = ucwords(\theme_remui\toolbox::get_setting('fontname'));
        }
        if (empty($fontname)) {
            $fontname = 'Open Sans';
        }
        $output .= "<link href='https://fonts.googleapis.com/css?family=$fontname:300,400,500,600,700,300italic' rel='stylesheet'
        type='text/css'>";

        // Add google analytics code.
        $gatrackingcode = trim(\theme_remui\toolbox::get_setting('googleanalytics'));

        if (!empty($gatrackingcode)) {
            $output .= "<!-- Global site tag (gtag.js) - Google Analytics --><script async src='https://www.googletagmanager.com/gtag/js?id=".$gatrackingcode."'></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());

              gtag('config', '".$gatrackingcode."');
            </script><!-- Google Analytics -->";
        }
        return $output;
    }

    /**
     * Returns the url of the custom favicon.
     */
    public function favicon() {
        $favicon = \theme_remui\toolbox::setting_file_url('faviconurl', 'faviconurl');
        if (empty($favicon)) {
            return \theme_remui\toolbox::image_url('favicon', 'theme');
        } else {
            return $favicon;
        }
    }

    /**
     * Renders the header bar.
     *
     * @param context_header $contextheader Header bar object.
     * @return string HTML for the header bar.
     */
    protected function render_context_header(\context_header $contextheader) {
        global $PAGE;

        // All the html stuff goes here.
        $html = '';
        $htmltemp = ''; // Prepare html based on overlay is on or off.

        // Moved from full_header for proper remui html structure.
        $pageheadingbutton = $this->page_heading_button();

        // Headings.
        if (!isset($contextheader->heading)) {
            $headings = $this->heading($this->page->heading, $contextheader->headinglevel, 'page-title font-size-24 mb-0');
        } else {
            $headings = $this->heading($contextheader->heading, $contextheader->headinglevel, 'page-title font-size-24 mb-0');
        }

        $html .= "<div class='mr-2'>";
        $html .= $headings;
        if (empty($PAGE->layout_options['nonavbar'])) {
            $html .= $this->navbar();
        } else {
            $html .= '<ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><p></p></a></li></ol>';
        }
        $html .= "</div>";

        // Little hack for now, to always show overlay buttons for mobile devices
        // will be transfered to html and css later.
        $actualdevice = \core_useragent::get_device_type();
        $currentdevice = $this->page->devicetypeinuse;
        $overlay = \theme_remui\toolbox::get_setting('enableheaderbuttons');
        if (!$overlay) {
            if ($actualdevice == 'mobile' && $currentdevice == 'mobile') {
                $overlay = 1;
            }
        }
        // Add heading and additional buttons in temp var
        // additional context header buttons.
        if ($overlay && !strpos($PAGE->bodyclasses, 'path-mod-forum')) {
            $htmltemp .= $pageheadingbutton;
        }
        if (isset($contextheader->additionalbuttons)) {
            foreach ($contextheader->additionalbuttons as $button) {
                if (!isset($button->page)) {
                    // Include js for messaging.
                    if ($button['buttontype'] === 'togglecontact') {
                        \core_message\helper::togglecontact_requirejs();
                    }

                    $image = $this->pix_icon($button['formattedimage'], $button['title'], 'moodle', array(
                        'class' => 'iconsize-button',
                        'role' => 'presentation'
                    ));

                    $image = html_writer::span($image.'&nbsp;&nbsp;'.$button['title']);
                } else {
                    $image = html_writer::empty_tag('img', array(
                        'src' => $button['formattedimage'],
                        'role' => 'presentation'
                    ));
                }

                $button['linkattributes']['class'] .= ' btn-secondary ';
                $htmltemp .= html_writer::start_tag('div', array('class' => 'singlebutton'));
                $htmltemp .= html_writer::link($button['url'], $image, $button['linkattributes']);
                $htmltemp .= html_writer::end_tag('div');
            }
        }

        // Page header actions.
        $html .= html_writer::start_div('page-header-actionss position-relative d-flex ml-1');
        $html .= $this->context_header_settings_menu();

        if ($overlay && strpos($PAGE->bodyclasses, 'path-mod-forum') || !$overlay) {
            $html .= $pageheadingbutton;
        }
        if (!$overlay) {
            $html .= $htmltemp;
        }

        // Show overlay menu icon if overlay is enabled and there are menu items (in html temp).
        if ($overlay) {
            if ($htmltemp != '') {
                $html .= '<span class="overlay-menu m-1 mr-1">';
                $html .= '<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-v px-1" aria-hidden="true"></i></button>';
                $html .= '<div class="dropdown-menu p-2 dropdown-menu-right">';
                $html .= $htmltemp;
                $html .= '</div>';
                $html .= '</span>';
            }
        }
        $html .= html_writer::end_div();
        return $html;
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {
        $html = html_writer::start_tag('header', array('id' => 'page-header', 'class' => 'row'));
        $html .= html_writer::start_tag('div', array('class' => 'col-12'));
        $html .= html_writer::start_tag('div', array('class' => 'card'));
        $html .= html_writer::start_tag('div', array('class' => 'card-body d-flex justify-content-between flex-wrap'));
        $html .= $this->context_header();
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('header');
        return $html;
    }

    /**
     * Returns standard navigation between activities in a course.
     *
     * @return string the navigation HTML.
     */
    public function activity_navigation() {

        $activitynavenable = get_config('theme_remui', 'activitynextpreviousbutton');
        if (!$activitynavenable) {
            return '';
        }

        // First we should check if we want to add navigation.
        $context = $this->page->context;
        if (($this->page->pagelayout !== 'incourse' && $this->page->pagelayout !== 'frametop') ||
        $context->contextlevel != CONTEXT_MODULE) {
            return '';
        }

        // If the activity is in stealth mode, show no links.
        if ($this->page->cm->is_stealth()) {
            return '';
        }

        // Get a list of all the activities in the course.
        $course = $this->page->cm->get_course();
        $modules = get_fast_modinfo($course->id)->get_cms();

        // Put the modules into an array in order by the position they are shown in the course.
        $mods = [];
        $activitylist = [];
        foreach ($modules as $module) {
            // Only add activities the user can access, aren't in stealth mode and have a url (eg. mod_label does not).
            if (!$module->uservisible || $module->is_stealth() || empty($module->url)) {
                continue;
            }
            $mods[$module->id] = $module;

            // No need to add the current module to the list for the activity dropdown menu.
            if ($module->id == $this->page->cm->id) {
                continue;
            }
            // Module name.
            $modname = $module->get_formatted_name();
            // Display the hidden text if necessary.
            if (!$module->visible) {
                $modname .= ' ' . get_string('hiddenwithbrackets');
            }
            // Module URL.
            $linkurl = new moodle_url($module->url, array('forceview' => 1));
            // Add module URL (as key) and name (as value) to the activity list array.
            $activitylist[$linkurl->out(false)] = $modname;
        }

        $nummods = count($mods);

        // If there is only one mod then do nothing.
        if ($nummods == 1) {
            return '';
        }

        // Get an array of just the course module ids used to get the cmid value based on their position in the course.
        $modids = array_keys($mods);

        // Get the position in the array of the course module we are viewing.
        $position = array_search($this->page->cm->id, $modids);

        $prevmod = null;
        $nextmod = null;

        // Check if we have a previous mod to show.
        if ($position > 0) {
            $prevmod = $mods[$modids[$position - 1]];
        }

        // Check if we have a next mod to show.
        if ($position < ($nummods - 1)) {
            $nextmod = $mods[$modids[$position + 1]];
        }

        $activitynav = new \core_course\output\activity_navigation($prevmod, $nextmod, $activitylist);
        if ($activitynav->prevlink) {
            $activitynav->prevlink->attributes['class'] = 'btn btn-inverse btn-sm';
            if ($activitynavenable == 1) {
                $activitynav->prevlink->text = get_string('activityprev', 'theme_remui');
            }
        }

        if ($activitynav->nextlink) {
            $activitynav->nextlink->attributes['class'] = 'btn btn-inverse btn-sm';
            if ($activitynavenable == 1) {
                $activitynav->nextlink->text = get_string('activitynext', 'theme_remui');
            }
        }

        $renderer = $this->page->get_renderer('core', 'course');
        return $renderer->render($activitynav);
    }

    /**
     * Return HTML for site announcement.
     *
     * @return string Site announcement HTML
     */
    public function render_site_announcement() {
        $enableannouncement = \theme_remui\toolbox::get_setting('enableannouncement');
        $announcement = '';
        if ($enableannouncement) {
            $type = \theme_remui\toolbox::get_setting('announcementtype');
            $message = \theme_remui\toolbox::get_setting('announcementtext');

            $announcement .= "<div class='alert alert-{$type} dark text-center rounded-0 site-announcement m-b-0'>";
            $announcement .= $message;
            $announcement .= "</div>";
        }
        return $announcement;
    }
}
