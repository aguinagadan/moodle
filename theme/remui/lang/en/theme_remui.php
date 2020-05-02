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
 * Language file.
 *
 * @package   theme_remui
 * @copyright 2016 Frédéric Massart
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['advancedsettings'] = 'Advanced settings';
$string['backgroundimage'] = 'Background image';
$string['backgroundimage_desc'] = 'The image to display as a background of the site. The background image you upload here will override the background image in your theme preset files.';
$string['brandcolor'] = 'Brand colour';
$string['brandcolor_desc'] = 'The accent colour.';
$string['bootswatch'] = 'Bootswatch';
$string['bootswatch_desc'] = 'A bootswatch is a set of Bootstrap variables and css to style Bootstrap';
$string['choosereadme'] = '
<div class="about-remui-wrapper text-center">
    <div class="about-remui m-auto" style="max-width: 1000px;">
        <h1 class="text-center">Welcome To Edwiser RemUI</h1><br>
        <h5 class="text-muted">
        Edwiser RemUI is the new revolution in Moodle User Experience. It has been suitably designed
        to elevate e-learning with custom layouts, simplified navigation, content creation & customization option. <br><br>
        We\'re sure you will enjoy the remodeled look!
        </h5>
        <div class="text-center mt-50">
        <img src="' . $CFG->wwwroot . '/theme/remui/pix/screenshot.jpg" class="w-full" alt="Edwiser RemUI screen shot" style="max-width: 100%;"/>
        </div>
        <br><br>
        <div class="text-center">
            <div class="btn-group text-center" role="group" aria-label="...">
              <div class="btn-group mr-1" role="group">
                <a href="https://knowledgebase.edwiser.org/en/category/edwiser-remui-theme-5sxjyd/" target="_blank" class="btn btn-primary btn-round">FAQ</a>
              </div>
              <div class="btn-group mr-1" role="group">
                <a href="https://edwiser.org/remui/documentation/" target="_blank" class="btn btn-primary btn-round">Documentation</a>
              </div>
              <div class="btn-group" role="group">
                <a href="https://edwiser.org/contact-us/" target="_blank" class="btn btn-primary btn-round">Support</a>
              </div>
            </div>
        </div>
        <br>
        <h1 class="text-center">Personalize Your Theme</h1>
        <h5 class="text-muted text-center">
            We understand that not every LMS is the same. We\'ll work with you to understand your needs, and design and develop a solution to meet your goals.
        </h5>
        <br><br>
        <div class="row wdm_generalbox">
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-cogs"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Theme Customization</h4>
                </div>
            </div>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Functionality Development</h4>
                </div>
            </div>
            <br>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-link"></i>
                </div>
                <div class="iconbox-content">
                    <h4>API Integration</h4>
                </div>
            </div>
            <div class="marketing-spots span3 col-lg-6 col-12">
                <div class="iconsquare">
                    <i class="fa fa-life-ring"></i>
                </div>
                <div class="iconbox-content">
                    <h4>LMS Consultancy</h4>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="text-center">
            <a class="btn btn-primary btn-lg" target="_blank" href="https://edwiser.org/contact-us/">Contact Us</a>&nbsp;&nbsp;
        </div>
    </div>
</div>
<br />';
$string['aboutremui'] = 'About Edwiser RemUI';
$string['currentinparentheses'] = '(current)';
$string['configtitle'] = 'Edwiser RemUI';
$string['fontsize'] = 'Theme base fontsize';
$string['fontsize_desc'] = 'Enter a fontsize in %';
$string['nobootswatch'] = 'None';
$string['pluginname'] = 'Edwiser RemUI';
$string['presetfiles'] = 'Additional theme preset files';
$string['presetfiles_desc'] = 'Preset files can be used to dramatically alter the appearance of the theme. See <a href="https://docs.moodle.org/dev/remui_Presets">remui presets</a> for information on creating and sharing your own preset files, and see the <a href="http://moodle.net/remui">Presets repository</a> for presets that others have shared.';
$string['preset'] = 'Theme preset';
$string['preset_desc'] = 'Pick a preset to broadly change the look of the theme.';
$string['privacy:metadata'] = 'The remui theme does not store any personal data about any user.';
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Use this field to provide SCSS or CSS code which will be injected at the end of the style sheet.';
$string['rawscsspre'] = 'Raw initial SCSS';
$string['rawscsspre_desc'] = 'In this field you can provide initialising SCSS code, it will be injected before everything else. Most of the time you will use this setting to define variables.';
$string['region-side-pre'] = 'Right';
$string['privacy:metadata:preference:draweropennav'] = 'The user\'s preference for hiding or showing the drawer menu navigation.';
$string['privacy:drawernavclosed'] = 'The current preference for the navigation drawer is closed.';
$string['privacy:drawernavopen'] = 'The current preference for the navigation drawer is open.';
/* Course view preference */
$string['privacy:metadata:preference:course_view_state'] = 'The type of display that the user prefers for list of courses';
$string['course_view_state_description_grid'] = 'To display the courses in grid format';
$string['course_view_state_description_list'] = 'To display the courses in list format';

/* Course view preference */
$string['privacy:metadata:preference:viewCourseCategory'] = 'The type of display that the user prefers for list of courses';
$string['viewCourseCategory_grid'] = 'To display the courses in grid format';
$string['viewCourseCategory_list'] = 'To display the courses in list format';

/* Aside right view preference */
$string['privacy:metadata:preference:aside_right_state'] = 'Whether the aside block on the right should be kept open or docked';
$string['aside_right_state_'] = 'To display the aside block on right as open'; // blank value
$string['aside_right_state_overrideaside'] = 'To display the aside block on right as docked'; // overrideaside

/* Menu view preference */
$string['privacy:metadata:preference:menubar_state'] = 'The type of display that the user prefers for the menu bar';
$string['menubar_state_fold'] = 'To display the menu bar as folded';
$string['menubar_state_unfold'] = 'To display the menu bar as unfolded';
$string['menubar_state_open'] = 'To display the menu bar as open';
$string['menubar_state_hide'] = 'To display the menu bar as hidden';

// Profile Page.
$string['administrator'] = 'Administrator';
$string['contacts'] = 'Contacts';
$string['blogentries'] = 'Blog Entries';
$string['discussions'] = 'Discussions';
$string['aboutme'] = 'About Me';
$string['courses'] = 'Courses';
$string['interests'] = 'Interests';
$string['institution'] = 'Department & Institution';
$string['location'] = 'Location';
$string['description'] = 'Description';
$string['editprofile'] = 'Edit Profile';
$string['start_date'] = 'Start date';
$string['complete'] = 'Complete';
$string['surname'] = 'Last Name';
$string['actioncouldnotbeperformed'] = 'Action could not be performed!';
$string['enterfirstname'] = 'Please enter your First Name.';
$string['enterlastname'] = 'Please enter your Last Name.';
$string['enteremailid'] = 'Please enter your Email ID.';
$string['enterproperemailid'] = 'Please enter proper Email ID.';
$string['detailssavedsuccessfully'] = 'Details Saved Successfully!';
$string['forgotpassword'] = 'Forgot Password?';

// Left Navigation Drawer.
$string['createarchivepage'] = 'Course Archive Page';
$string['createanewcourse'] = 'Create A New Course';
$string['remuisettings'] = 'RemUI Settings';

// Right Navigation Drawer.
$string['navbartype'] = 'Navbar Color';
$string['sidebarcolor'] = 'Sidebar Color';
$string['sitecolor'] = 'Site Color';
$string['applysitewide'] = 'Apply Sitewide';
$string['applysitecolor'] = 'Apply Site Color';
$string['sidebarpinned'] = 'Sidebar pinned.';
$string['sidebarunpinned'] = 'Sidebar unpinned.';
$string['pinsidebar'] = 'Pin sidebar';
$string['unpinsidebar'] = 'Unpin sidebar';

// General Settings.
$string['generalsettings'] = 'General settings';
$string['enableannouncement'] = "Enable Site Announcement";
$string['enableannouncementdesc'] = "Enable a sitewide announcement for site visitors/students.";
$string['announcementtext'] = "Announcement";
$string['announcementtextdesc'] = "Announcement message to be displayed sitewide.";
$string['announcementtype'] = "Announcement type";
$string['announcementtypedesc'] = "info/alert/danger/success";
$string['typeinfo'] = "Information announcement";
$string['typedanger'] = "Urgent announcement";
$string['typewarning'] = "Warning announcement";
$string['typesuccess'] = "Success announcement";
$string['enablerecentcourses'] = 'Enable Recent Courses';
$string['enablerecentcoursesdesc'] = 'If enabled, Recent courses drop down menu will be displayed in header.';
$string['enableheaderbuttons'] = 'Show header buttons in dropdown';
$string['enableheaderbuttonsdesc'] = 'All the buttons which are displayed in header are converted to a single dropdown.';
$string['mergemessagingsidebar'] = 'Merge Message Panel';
$string['mergemessagingsidebardesc'] = 'Merge message panel into right sidebar';
$string['courseperpage'] = 'Courses Per Page';
$string['courseperpagedesc'] = 'Number of Courses to be Displayed Per Pages in Course Archive Page.';
$string['none'] = 'None';
$string['fade'] = 'Fade';
$string['slide-top'] = 'Slide Top';
$string['slide-bottom'] = 'Slide Bottom';
$string['slide-right'] = 'Slide Right';
$string['scale-up'] = 'Scale Up';
$string['scale-down'] = 'Scale Down';
$string['courseanimation'] = 'Course Animation';
$string['courseanimationdesc'] = 'Enabling this will add animation for course archieve page Courses';
$string['enablenewcoursecards'] = 'Enable New Course Cards';
$string['enablenewcoursecardsdesc'] = 'Enabling this will show new Course Cards on Course Archive Page';
$string['activitynextpreviousbutton'] = 'Enable Next/Previous activity button';
$string['activitynextpreviousbuttondesc'] = 'Next/Previous activity button appears on the top of activity for quick switch';
$string['disablenextprevious'] = 'Disable';
$string['enablenextprevious'] = 'Enable';
$string['enablenextpreviouswithname'] = 'Enable with Activity name';
$string['logoorsitename'] = 'Choose site logo format';
$string['logoorsitenamedesc'] = 'You can change the site header logo as per your choice. <br />The options available are: Logo - Only the logo will be shown; <br /> Icon+sitename - An icon along with the sitename will be shown.';
$string['onlylogo'] = 'Logo Only';
$string['iconsitename'] = 'Icon and sitename';
$string['logo'] = 'Logo';
$string['logodesc'] = 'You may add the logo to be displayed on the header. Note- Preferred height is 50px. In case you wish to customise, you can do so from the custom CSS box.';
$string['logomini'] = 'LogoMini';
$string['logominidesc'] = 'You may add the logomini to be displayed on the header when sidebar is collapsed. Note- Preferred height is 50px. In case you wish to customise, you can do so from the custom CSS box.';
$string['siteicon'] = 'Site icon';
$string['siteicondesc'] = 'Don\'t have a logo? You could choose one from this <a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_new">list</a>. <br /> Just enter the text after "fa-".';
$string['customcss'] = 'Custom CSS';
$string['customcssdesc'] = 'You may customise the CSS from the text box above. The changes will be reflected on all the pages of your site.';
$string['favicon'] = 'Favicon';
$string['favicondesc'] = 'Your site’s “favourite icon”. Here, you may insert the favicon for your site.';
$string['fontselect'] = 'Font type selector';
$string['fontselectdesc'] = 'Choose from either Standard fonts or <a href="https://fonts.google.com/" target="_new">Google web fonts</a> types. Please save to show the options for your choice.';
$string['fonttypestandard'] = 'Standard font';
$string['fonttypegoogle'] = 'Google web font';
$string['fontname'] = 'Site Font';
$string['fontnamedesc'] = 'Enter the exact name of the font to use for Moodle.';
$string['googleanalytics'] = 'Google Analytics Tracking ID';
$string['googleanalyticsdesc'] = 'Please enter your Google Analytics Tracking ID to enable analytics on your website. The  tracking ID format shold be like [UA-XXXXX-Y].<br/>Please be aware that by including this setting, you will be sending data to Google Analytics and you should make sure that your users are warned about this. Our product does not store any of the data being sent to Google Analytics.';
$string['enablecoursestats'] = 'Enable Course Stats';
$string['enablecoursestatsdesc'] = 'If enabled, Administrator, Managers and teacher will see the stats related to course on course page.';
$string['courseeditbuttonsetting'] = 'Course edit button';
$string['courseeditbuttonsetting_desc'] = 'With this setting you can add an additional course edit on / off button to the course header for faster accessibility.';
$string['enabledictionary'] = 'Enable Dictionary';
$string['enabledictionarydesc'] = 'If enabled, Dictionary feature will be activated and which will show the meaning of selected text in popup.';
$string['more'] = 'More...';


// Frontpage Old String
// Home Page Settings.
$string['homepagesettings'] = 'Home Page Settings';
$string['frontpagedesign'] = 'Frontpage Design';
$string['frontpagedesigndesc'] = 'This section relates to the design style of frontpage.';
$string['frontpagechooser'] = 'Choose frontpage design';
$string['frontpagechooserdesc'] = 'Choose your frontpage design.';
$string['frontpagedesignold'] = 'Default old design';
$string['frontpagedesignolddesc'] = 'Default dashboard like previous.';
$string['frontpagedesignnew'] = 'New design';
$string['frontpagedesignnewdesc'] = 'Fresh new design with multiple sections. You can configure sections individualy on frontpage.';
$string['newhomepagedescription'] = 'Click on \'Site Home\' from the Navigation bar to go to \'Homepage Builder\' and create your own Homepage';
$string['frontpageloader'] = 'Upload loader image for frontpage';
$string['frontpageloaderdesc'] = 'This replace the default loader with your image';
$string['frontpageimagecontent'] = 'Header content';
$string['frontpageimagecontentdesc'] = ' This section relates to the top portion of your frontpage.';
$string['frontpageimagecontentstyle'] = 'Style';
$string['frontpageimagecontentstyledesc'] = 'You can choose between Static & Slider.';
$string['staticcontent'] = 'Static';
$string['slidercontent'] = 'Slider';
$string['addtext'] = 'Add Text';
$string['defaultaddtext'] = 'Education is a time-tested path to progress.';
$string['addtextdesc'] = 'Here you may add the text to be displayed on the front page, preferably in HTML.';
$string['uploadimage'] = 'Upload Image';
$string['uploadimagedesc'] = 'You may upload image as content for slide';
$string['video'] = 'iframe Embedded code';
$string['videodesc'] = ' Here, you may insert the iframe Embedded code of the video that is to be embedded.';
$string['contenttype'] = 'Select Content type';
$string['contentdesc'] = 'You can choose between image or give video url.';
$string['imageorvideo'] = 'Image/ Video';
$string['image'] = 'Image';
$string['videourl'] = 'Video URL';
$string['slideinterval'] = 'Slide interval';
$string['slideintervalplaceholder'] = 'Positive integer number in milliseconds.';
$string['slideintervaldesc'] = 'You may set the transition time between the slides. In case if there is one slide, this option will have no effect. If interval is invalid(empty|0|less than 0) then default interval is 5000 milliseconds.';
$string['slidercount'] = 'No of slides';
$string['slidercountdesc'] = '';
$string['one'] = '1';
$string['two'] = '2';
$string['three'] = '3';
$string['four'] = '4';
$string['five'] = '5';
$string['eight'] = '8';
$string['twelve'] = '12';
$string['slideimage'] = 'Upload images for Slider';
$string['slideimagedesc'] = 'You may upload an image as content for this slide.';
$string['sliderurl'] = 'Add link to Slider button';
$string['slidertext'] = 'Add Slider text';
$string['defaultslidertext'] = '';
$string['slidertextdesc'] = 'You may insert the text content for this slide. Preferably in HTML.';
$string['sliderbuttontext'] = 'Add Text button on slide';
$string['sliderbuttontextdesc'] = 'You may add text to the button on this slide.';
$string['sliderurldesc'] = 'You may insert the link of the page where the user will be redirected once they click on the button.';
$string['sliderautoplay'] = 'Set Slider Autoplay';
$string['sliderautoplaydesc'] = 'Select ‘yes’ if you want automatic transition in your slideshow.';
$string['true'] = 'Yes';
$string['false'] = 'No';
$string['frontpageblocks'] = 'Body Content';
$string['frontpageblocksdesc'] = 'You may insert a heading for your site’s body';
$string['frontpageblockdisplay'] = 'About Us Section';
$string['frontpageblockdisplaydesc'] = 'You can show or hide the "About Us" section, also you can show "About Us" section in grid format';
$string['donotshowaboutus'] = 'Do Not Show';
$string['showaboutusinrow'] = 'Show Section in a Row';
$string['showaboutusingridblock'] = 'Show Section in Grid Block';

// About Us.
$string['frontpageaboutus'] = 'Frontpage About us';
$string['frontpageaboutusdesc'] = 'This section is for front page About us';
$string['frontpageaboutustitledesc'] = 'Add title to About Us Section';
$string['frontpageaboutusbody'] = 'Body Description for About Us Section';
$string['frontpageaboutusbodydesc'] = 'A brief description about this Section';
$string['enablesectionbutton'] = 'Enable buttons on Sections';
$string['enablesectionbuttondesc'] = 'Enable the buttons on body sections.';
$string['sectionbuttontextdesc'] = 'Enter the text for button in this Section.';
$string['sectionbuttonlinkdesc'] = 'Enter the URL link for this Section.';
$string['frontpageblocksectiondesc'] = 'Add title to this Section.';
/* block section 1 */
$string['frontpageblocksection1'] = 'Body title for 1st Section';
$string['frontpageblockdescriptionsection1'] = 'Body description for 1st Section';
$string['frontpageblockiconsection1'] = 'Font-Awesome icon for 1st Section';
$string['sectionbuttontext1'] = 'Button text for 1st Section';
$string['sectionbuttonlink1'] = 'URL link for 1st Section';
/* block section 2 */
$string['frontpageblocksection2'] = 'Body title for 2nd Section';
$string['frontpageblockdescriptionsection2'] = 'Body description for 2nd Section';
$string['frontpageblockiconsection2'] = 'Font-Awesome icon for 2nd Section';
$string['sectionbuttontext2'] = 'Button text for 2nd Section';
$string['sectionbuttonlink2'] = 'URL link for 2nd Section';
/* block section 3 */
$string['frontpageblocksection3'] = 'Body title for 3rd Section';
$string['frontpageblockdescriptionsection3'] = 'Body description for 3rd Section';
$string['frontpageblockiconsection3'] = 'Font-Awesome icon for 3rd Section';
$string['sectionbuttontext3'] = 'Button text for 3rd Section';
$string['sectionbuttonlink3'] = 'URL link for 3rd Section';
/* block section 4 */
$string['frontpageblocksection4'] = 'Body title for 4th Section';
$string['frontpageblockdescriptionsection4'] = 'Body description for 4th Section';
$string['frontpageblockiconsection4'] = 'Font-Awesome icon for 4th Section';
$string['sectionbuttontext4'] = 'Button text for 4th Section';
$string['sectionbuttonlink4'] = 'URL link for 4th Section';
$string['defaultdescriptionsection'] = 'Holisticly harness just in time technologies via corporate scenarios.';
$string['frontpageaboutus'] = 'Frontpage About us';
$string['frontpageaboutusdesc'] = 'This section is for front page About us';
$string['enablefrontpageaboutus'] = 'Enable About us section';
$string['enablefrontpageaboutusdesc'] = 'Enable the About us section in front page.';
$string['frontpageaboutusheading'] = 'About us Heading';
$string['frontpageaboutusheadingdesc'] = 'Heading for the default heading text for section';
$string['frontpageaboutustext'] = 'About us text';
$string['frontpageaboutustextdesc'] = 'Enter about us text for frontpage.';
$string['frontpageaboutusdefault'] = '<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
              Ut enim ad minim veniam.</p>';
$string['testimonialcount'] = 'Testimonial Count';
$string['testimonialcountdesc'] = 'Number of testimonials to show.';
$string['testimonialimage'] = 'Testimonial Image';
$string['testimonialimagedesc'] = 'Person image to display with testimonial';
$string['testimonialname'] = 'Person Name';
$string['testimonialnamedesc'] = 'Name of person';
$string['testimonialdesignation'] = 'Person Designation';
$string['testimonialdesignationdesc'] = 'Person\'s designation.';
$string['testimonialtext'] = 'Person\'s Testimonial';
$string['testimonialtextdesc'] = 'What person says';
$string['frontpageblockimage'] = 'Upload image';
$string['frontpageblockimagedesc'] = 'You may upload an image as content for this.';
$string['frontpageblockiconsectiondesc'] = 'You can choose any icon from this <a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_new">list</a>. Just enter the text after "fa-". ';
$string['frontpageblockdescriptionsectiondesc'] = 'A brief description about the title.';


// Footer Page Settings.
$string['footersettings'] = 'Footer Settings';
$string['socialmedia'] = 'Social Media Settings';
$string['socialmediadesc'] = 'Enter the social media links for your site.';
$string['facebooksetting'] = 'Facebook Settings';
$string['facebooksettingdesc'] = 'Enter your site\'s facebook page link. For eg. https://www.facebook.com/pagename';
$string['twittersetting'] = 'Twitter Settings';
$string['twittersettingdesc'] = 'Enter your site\'s twitter page link. For eg. https://www.twitter.com/pagename';
$string['linkedinsetting'] = 'Linkedin Settings';
$string['linkedinsettingdesc'] = 'Enter your site\'s linkedin page link. For eg. https://www.linkedin.com/in/pagename';
$string['gplussetting'] = 'Google Plus Settings';
$string['gplussettingdesc'] = 'Enter your site\'s Google Plus page link. For eg. https://plus.google.com/pagename';
$string['youtubesetting'] = 'YouTube Settings';
$string['youtubesettingdesc'] = 'Enter your site\'s YouTube page link. For eg. https://www.youtube.com/channel/UCU1u6QtAAPJrV0v0_c2EISA';
$string['instagramsetting'] = 'Instagram Settings';
$string['instagramsettingdesc'] = 'Enter your site\'s Instagram page link. For eg. https://www.instagram.com/name';
$string['pinterestsetting'] = 'Pinterest Settings';
$string['pinterestsettingdesc'] = 'Enter your site\'s Pinterest page link. For eg. https://www.pinterest.com/name';
$string['quorasetting'] = 'Quora Settings';
$string['quorasettingdesc'] = 'Enter your site\'s Quora page link. For eg. https://www.quora.com/name';
$string['footerbottomtext'] = 'Footer Bottom-Left Text';
$string['footerbottomlink'] = 'Footer Bottom-Left Link';
$string['footerbottomlinkdesc'] = 'Enter the Link for the bottom-left section of Footer. For eg. http://www.yourcompany.com';
$string['footercolumn1heading'] = 'Footer Content for 1st Column (Left)';
$string['footercolumn1headingdesc'] = 'This section relates to the bottom portion (Column 1) of your frontpage.';
$string['footercolumn1title'] = '1st Footer Column title';
$string['footercolumn1titledesc'] = 'Add title to this column.';
$string['footercolumn1customhtml'] = 'Custom HTML';
$string['footercolumn1customhtmldesc'] = 'You can customize HTML of this column using above given textbox.';
$string['footercolumn2heading'] = 'Footer Content for 2nd Column (Middle)';
$string['footercolumn2headingdesc'] = 'This section relates to the bottom portion (Column 2) of your frontpage.';
$string['footercolumn2title'] = '2nd Footer Column Title';
$string['footercolumn2titledesc'] = 'Add title to this column.';
$string['footercolumn2customhtml'] = 'Custom HTML';
$string['footercolumn2customhtmldesc'] = 'You can customize HTML of this column using above given textbox.';
$string['footercolumn3heading'] = 'Footer Content for 3rd Column (Right)';
$string['footercolumn3headingdesc'] = 'This section relates to the bottom portion (Column 3) of your frontpage.';
$string['footercolumn3title'] = '3rd Footer Column Title';
$string['footercolumn3titledesc'] = 'Add title to this column.';
$string['footercolumn3customhtml'] = 'Custom HTML';
$string['footercolumn3customhtmldesc'] = 'You can customize HTML of this column using above given textbox.';
$string['footerbottomheading'] = 'Bottom Footer Setting';
$string['footerbottomdesc'] = 'Here you can specify your own link you want to enter at the bottom section of Footer';
$string['footerbottomtextdesc'] = 'Add text to Bottom Footer Setting.';
$string['poweredbyedwiser'] = 'Powered by Edwiser';
$string['poweredbyedwiserdesc'] = 'Uncheck to remove  \'Powered by Edwiser\' from your site.';

// Login Page Settings.
$string['loginsettings'] = 'Login Page Settings';
$string['navlogin_popup'] = 'Enable Login Popup';
$string['navlogin_popupdesc'] = 'Enable login popup in header.';
$string['loginsettingpic'] = 'Upload Background Image';
$string['loginsettingpicdesc'] = 'Upload image as a background for login form.';
$string['signuptextcolor'] = 'Signup panel text color';
$string['signuptextcolordesc'] = 'Select the text color for Signup panel.';
$string['left'] = "Left side";
$string['right'] = "Right side";
$string['remember_me'] = "Remember Me";
$string['brandlogopos'] = "Brand Logo Position";
$string['brandlogoposdesc'] = "If enabled, Brand logo will be visible on right sidebar above the login form.";
$string['brandlogotext'] = "Site Description";
$string['brandlogotextdesc'] = "Add text for site description which will display on login and signup page. Keep this blank if don't want to put any description.";
$string['loginpagelayout'] = 'Login page layout';
$string['loginpagelayoutdesc'] = '';
$string['centerlayout'] = 'Centered Layout';
$string['overlaylayout'] = 'Right Overlay Layout';

// License Settings.
$string['licensenotactive'] = '<strong>Alert!</strong> License is not activated , please <strong>activate</strong> the license in RemUI settings.';
$string['licensenotactiveadmin'] = '<strong>Alert!</strong> License is not activated , please <strong>activate</strong> the license <a href="'.$CFG->wwwroot.'/admin/settings.php?section=themesettingremui#remuilicensestatus" >here</a>.';
$string['activatelicense'] = 'Activate License';
$string['deactivatelicense'] = 'Deactivate License';
$string['renewlicense'] = 'Renew License';
$string['active'] = 'Active';
$string['notactive'] = 'Not Active';
$string['expired'] = 'Expired';
$string['licensekey'] = 'License key';
$string['licensestatus'] = 'License Status';
$string['noresponsereceived'] = 'No response received from the server. Please try again later.';
$string['licensekeydeactivated'] = 'License Key is deactivated.';
$string['siteinactive'] = 'Site inactive (Press Activate license to activate plugin).';
$string['entervalidlicensekey'] = 'Please enter valid license key.';
$string['nolicenselimitleft'] = 'Maximum activation limit reached, No activations left.';
$string['licensekeyisdisabled'] = 'Your license key is Disabled.';
$string['licensekeyhasexpired'] = "Your license key has Expired. Please, Renew it.";
$string['licensekeyactivated'] = "Your license key is activated.";
$string['enterlicensekey'] = "Please enter correct license key.";
$string['edwiserremuilicenseactivation'] = 'Edwiser RemUI License Activation';

// News And Updates Page.
$string['newsandupdates'] = 'News & Updates';
$string['newupdatemessage'] = 'New Update Available for RemUI.';
$string['currentversionmessage'] = 'Your current version is ';
$string['downloadupdate'] = 'Download Update';
$string['latestversionmessage'] = 'You are using the latest version of RemUI.';
$string['rateremui'] = 'Rate RemUI';
$string['fullname']  = 'Full Name';
$string['providefeedback'] = 'Please provide your feedback about RemUI';
$string['sendfeedback'] = 'Send Feedback';
$string['recentnews'] = 'Recent News';

// About Edwiser RemUI.
$string['aboutsettings'] = 'About Edwiser RemUI';
$string['notenrolledanycourse'] = 'Not enrolled in any course.';

/* My Course Page */
$string['resume'] = 'Resume';
$string['start'] = 'Start';
$string['completed'] = 'Completed';

// Course.
$string['graderreport'] = 'Grader Report';
$string['enroluser'] = 'Enrol Users';
$string['activityeport'] = 'Activity Report';
$string['editcourse'] = 'Edit Course';
$string['sections'] = "Sections";
// Next Previous Activity.
$string['activityprev'] = 'Previous Activity';
$string['activitynext'] = 'Next Activity';

// Login Page.
$string['signin'] = 'Sign In';
$string['signup'] = 'Sign Up';
$string['noaccount'] = 'No Account?';

// Incourse Page.
$string['backtocourse'] = 'Course Overview';

// Header Section.
$string['togglefullscreen'] = 'Toggle fullscreen';
$string['recent'] = 'Recent';

// Course Stats.
$string['enrolledusers'] = 'Enrolled <br>Students';
$string['studentcompleted'] = 'Students <br>Completed';
$string['inprogress'] = 'In <br>Progress';
$string['yettostart'] = 'Yet <br>to Start';

// Footer Content.
$string['followus'] = 'Follow Us';
$string['poweredby'] = 'Powered by Edwiser RemUI';

// Course Archive Page.
$string['mycourses'] = "My Courses";
$string['allcategories'] = 'All categories';
$string['categorysort'] = 'Sort Categories';
$string['sortdefault'] = 'Sort (none)';
$string['sortascending'] = 'Sort A to Z';
$string['sortdescending'] = 'Sort Z to A';

// Dashboard Blocks.
$string['viewcourse'] = "VIEW COURSE";
$string['searchcourses'] = "Search courses";

$string['hiddencourse'] = 'Hidden Course';

// Usage tracking
$string['enableusagetracking'] = "Enable Usage Trakcing";
$string['enableusagetrackingdesc'] = "<strong>USAGE TRACKING NOTICE</strong>

<hr class='text-muted' />

<p>Edwiser from now on will collect anonymous data to generate product usage statistics.</p>

<p>This information will help us guide the development in right direction and the Edwiser community prosper.</p>

<p>Having said that we don't gather your personal data or of your students during this process. You can disable this from the plugin whenever you wish to opt out of this service.</p>

<p>An overview of the data collected is available <strong><a href='https://forums.edwiser.org/topic/67/anonymously-tracking-the-usage-of-edwiser-products' target='_blank'>here</a></strong>.</p>";