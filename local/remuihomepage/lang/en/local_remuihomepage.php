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
 * Edwiser RemUI Homepage Builder
 * @package    local_remuihomepage
 * @copyright  (c) 2019 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Edwiser RemUI Homepage Builder';
$string['homepagesettings'] = 'Home Page Settings';
$string['none'] = 'None';
$string['fade'] = 'Fade';
$string['slide-top'] = 'Slide Top';
$string['slide-bottom'] = 'Slide Bottom';
$string['slide-right'] = 'Slide Right';
$string['slide-left'] = 'Slide Left';
$string['slide-left-right'] = 'Alternate: Slide Left and Slide Right';
$string['scale-up'] = 'Scale Up';
$string['scale-down'] = 'Scale Down';
$string['courseanimation'] = 'Course Animation';
$string['courseanimationdesc'] = 'Enabling this will add Animation to Courses in the Course Archive Page Courses';
$string['addsection'] = 'Click to Add Section';
$string['publishfrontpage'] = 'Publish';
$string['sectiondelete'] = 'This section will be permanently deleted in 30 seconds, undo to avoid any changes';
$string['undo'] = 'Undo within';
$string['frontpageheadercolor'] = 'Homepage header color';
$string['frontpageheadercolordesc'] = 'If header is transparent then choosen color will be applied to page header.';
$string['frontpagetransparentheader'] = 'Homepage transparent header';
$string['frontpagetransparentheaderdesc'] = 'When slider is the first section on homepage, then header will appear as transparent.';
$string['frontpageappearanimation'] = 'Section appear animation';
$string['frontpageappearanimationdesc'] = 'Enable this to activate appear animation for sections.';
$string['frontpageappearanimationstyle'] = 'Appear animation style';
$string['frontpageappearanimationstyledesc'] = 'Choose animation style for section.';
$string['migrate'] = 'Migrate';
$string['migratedesc'] = 'Migrate all previous data from default homepage';
$string['sectionupdated'] = 'Section updated successfully. Publish to apply changes.';
$string['sectiondeleted'] = 'Section deleted successfully. Publish to apply changes.';
$string['frontpageloader'] = 'Upload loader image for frontpage';
$string['frontpageloaderdesc'] = 'This replace the default loader with your image';

// Slider Section.
$string['noofslides'] = 'Number of slides';
$string['slideheading'] = "Slide Heading";
$string['slideheadingplaceholder'] = 'Enter slide heading here';
$string['slidedescription'] = "Slide Description";
$string['slidedescriptionplaceholder'] = 'Enter slide description here';
$string['btnlabel'] = 'Button Label';
$string['btnlink'] = 'Button Link';
$string['missingslide'] = 'Please upload image or video';
$string['slideinterval'] = 'Slide interval';
$string['slideintervalplaceholder'] = 'Positive integer number in milliseconds.';
$string['slideintervaldesc'] = 'You may set the transition time between the slides. In case if there is one slide, this option will have no effect. If interval is invalid(empty|0|less than 0) then default interval is 5000 milliseconds.';
$string['imageorvideo'] = 'Image/ Video';


// Contact Section.
$string['contactlink'] = 'Contact Link';
$string['contactus'] = 'Contact us';
$string['email'] = 'Email';
$string['name'] = "Name";
$string['contactplaceholder'] = 'Enter Contact detail, this can be anything like email or phone';
$string['missingcontactlink'] = 'Missing contact link';
$string['title'] = 'Title';
$string['titleplaceholder'] = 'Enter title here';
$string['missingtitle'] = 'Missing title';
$string['description'] = 'Description';
$string['descriptionplaceholder'] = 'Enter description here';
$string['contactlabelplaceholder'] = 'Enter Label e.g. Email, Phone, etc.';
$string['missingdescription'] = 'Missing description';
$string['socialview'] = 'Icons View';
$string['quora'] = 'Quora';
$string['google'] = 'Google';
$string['youtube'] = 'Youtube';
$string['twitter'] = 'Twitter';
$string['facebook'] = 'Facebook';
$string['linkedin'] = 'Linkedin';
$string['pinterest'] = 'Pinterest';
$string['instagram'] = 'Instagram';


// General Strings.
$string['sectioncustomcssdesc'] = 'Add custom css styles. Ex.
div {
    background: rgba(0, 0, 0, 0.5);
}';
$string['sectionpadding'] = 'Section Padding In pixel';
$string['sectionsetting'] = 'Section Settings';
$string['sectionbackground'] = 'Section Background Image';
$string['bgcolor'] = 'Background Color';
$string['bgfixed'] = 'Fixed Background';
$string['bgopacity'] = 'Background Opacity';
$string['nobgfixed'] = 'Not Fixed Background';
$string['textbold'] = 'Bold';
$string['textitalic'] = 'Italic';
$string['titleeditor'] = 'Editor';
$string['fontsize'] = 'Font Size';
$string['textunderline'] = 'Underline';
$string['color'] = 'Color';
$string['editingison'] = 'Editing Mode On';
$string['fullwidth'] = 'Full Width';
$string['container'] = 'Container Fixed Width';
$string['shadowless'] = 'Section Elements Shadow';
$string['shadowcolor'] = 'Section Shadow Color';
$string['shadowlessdesc'] = 'Enable this to add some shadow to section elements';
$string['contactlabel'] = "Contact Label";
$string['link'] = 'Link';
$string['linklabel'] = 'Link Label';
$string['phone'] = 'Contact No.';

// Section list string.
$string['slidersection'] = "Slider Section";
$string['aboutussection'] = "About us Section";
$string['contactsection'] = "Contact Section";
$string['featuresection'] = "Feature Section";
$string['coursessection'] = "Courses Section";
$string['teamsection'] = "Team Section";
$string['testimonialsection'] = "Testimonial Section";
$string['htmlsection'] = "Html Section";
$string['separatorsection'] = "Separator Section";


// Slider Section.
$string['textalign'] = 'Text Align';
$string['desccolor'] = 'Description Color';
$string['headingcolor'] = 'Heading Color';
$string['enablenav'] = 'Navigation Arrows';

$string['nonav'] = 'No Navigation Arrows';
$string['navarrows'] = 'Default Nav Arrows';
$string['navarrowscircle'] = 'Navigation Arrows with circular Background';
$string['navarrowssquare'] = 'Navigation Arrows with square Background';

// Team Section.
$string['meetourteam'] = 'Meet our team';
$string['rows'] = 'Number of Rows';
$string['members'] = 'Number of Members';
$string['image'] = 'Select Image';
$string['quote'] = 'Enter Quote';
$string['teammembernameplaceholder'] = "Enter team member's name here";
$string['teammemberquoteplaceholder'] = "Enter team member's quote here";

// Feature Section.
$string['feature'] = 'Feature';
$string['features'] = 'Number of Features';
$string['featurenameplaceholder'] = 'Enter feature here';
$string['missingname'] = 'Missing name';
$string['featureiconplaceholder'] = 'Enter feature icon here';
$string['missingicon'] = 'Missing icon';
$string['colorhex'] = 'Hex value for color';

// Courses section.
$string['all'] = 'All';
$string['allcourses'] = 'All courses';
$string['future'] = 'Future';
$string['coursessectioninprogress'] = 'In progress';
$string['past'] = 'Past';
$string['coursecategoriesplaceholder'] = 'Search course category here';
$string['categories'] = 'Categories';
$string['categoryandcourses'] = 'Category and Courses';
$string['hiddencategory'] = 'Hidden Category';

// Testimonial Section.
$string['testimonials'] = 'Number of testimonials';
$string['testimonial'] = 'Testimonial';
$string['testimonialplaceholder'] = "Enter person's testimonial here";
$string['missingtestimonial'] = 'Missing Testimonial';
$string['designation'] = 'Designation';
$string['designationplaceholder'] = "Enter person's designation here";
$string['borderradius'] = 'Border Radius';
$string['noradius'] = 'No Border Radius';
$string['px'] = ' Pixel';
$string['fullnameplaceholder'] = "Enter person's full name here";
$string['namecolor'] = 'Author Name field Color';
$string['namecolordesc'] = 'This color will be applied to all Fullname Text';
$string['designationcolor'] = 'Designation Field Color';
$string['designationcolordesc'] = 'This color will be applied to all Designation Text.';
$string['testimonialcolor'] = 'Testimonial Field Color';
$string['testimonialcolordesc'] = 'This color will be applied to all Testimonial Text.';
$string['testimonialproperties'] = 'Text properties for testimonial';
$string['testimonialpropertiesdesc'] = 'These properties will be applied to all author\'s testimonial.';
$string['backgroundstyle'] = 'Testimonial background style';
$string['solidcolor'] = 'Solid';
$string['gradientcolor'] = 'Gradient';
$string['testimonialcolor1'] = 'If background style is solid then this color will be applied for whole testimonial. If background style is gradient then this will be the first color.';
$string['testimonialcolor2'] = 'This will be the second color for testimonial background.';
$string['layout1'] = 'Layout 1';
$string['layout2'] = 'Layout 2';

// Edit Menu.
$string['edit'] = 'Edit';
$string['moveup'] = 'Move Up';
$string['movedown'] = 'Move Down';
$string['hide'] = 'Hide';
$string['show'] = 'Show';
$string['delete'] = 'Delete';

// HTML Section.
$string['blocks'] = 'Number of blocks';
$string['cssstyle'] = 'CSS Styles';
$string['cssstyleplaceholder'] = 'Enter css styles here. Live changes will reflected into the editor. Ex:
div {
    border: 2px dashed #ccc;
}
';
$string['htmldefaultcontent'] = 'Put your content here';
$string['applyfilter'] = 'Apply filters';
$string['applyfilterdesc'] = 'Apply moodle filters on content before showing section.';

// Separator Section.
$string['separatorstyle'] = 'Separator style';
$string['separatorsolid'] = 'Solid';
$string['separatordouble'] = 'Double';
$string['separatordashed'] = 'Dashed';
$string['separatordotted'] = 'Dotted';
$string['separatorblur'] = 'Blur';
$string['separatorblurend'] = 'Blur end';
$string['separatorgradient'] = 'Gradient';
$string['separatorwidth'] = 'Width in percentage';
$string['separatorheight'] = 'Height';
$string['separatorresult'] = 'Result';

// About us section.
$string['aboutus'] = 'About us';
$string['aboutusblock'] = 'About us block ';
$string['view'] = 'View';
$string['icon'] = 'Icon (<a href="https://fontawesome.com/v4.7.0/cheatsheet/" target="_new">Font-Awesome</a>)';
$string['aboutusicondesc'] = 'You can choose any icon from this <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_new">list</a>. Just enter the text after "fa-".';
$string['backgroudimage'] = 'Background Image';
$string['block'] = 'Block';
$string['rowview'] = "Row";
$string['gridview'] = "Grid";
$string['columnview'] = 'Column';
$string['clickhere'] = 'Click Here';
$string['btnlink'] = "Button Link";
$string['btnlinkplaceholder'] = 'Enter button link here';
$string['btnlabel'] = "Button Label";
$string['btnlabelplaceholder'] = 'Enter button label here';
$string['colorhex'] = 'Color ( Hex code )';
$string['colorhexdesc'] = 'Click on above box to choose color';
$string['blockbackground'] = 'Block Background';
$string['transparent'] = 'Transparent';
$string['noborder'] = 'No Border';
$string['border'] = 'Bordered';
$string['cardradius'] = 'Card Radius';

// Custom CSS
$string['customcss'] = 'Custom CSS';
$string['customcssdesc'] = 'You may customise the CSS from the text box above. The changes will be reflected on all the pages of your site.';
