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
define('SEC_TEAM', 'section_team');
use context_system;

trait team_form {

    /**
     * Get the validation js script for team form
     * @return string html string which contain js code for validation
     */
    private function team_form_validation_js() {
        ob_start();
        ?>
        <script type="text/javascript">
            var remui_section_form_validate = function(root) {
                var valid = true;
                var rows = $(root).find('#id_rows').val();
                var scrolled = false;
                var scrollTo = function(element) {
                    if (!scrolled) {
                        element.closest('.modal-content').animate({
                            scrollTop: element.closest('.felement').position().top
                        }, 0);
                        scrolled = true;
                    }
                }
                var members;
                var element;
                for (var i = 0; i < rows; i++) {
                    members = $(root).find('#id_row_' + i + '_members').val();
                    for (var j = 0; j < members; j++) {
                        element = $(root).find('#id_row_' + i + '_member_' + j + '_name_text');
                        if ($(element).val() == '') {
                            valid = false;
                            scrollTo(element);
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
    private function teamform(&$mform, $formdata, $dbformdata) {
        global $USER, $CFG;
        require_once($CFG->libdir.'/adminlib.php');

        $mform->addElement('html', $this->team_form_validation_js());

        $this->add_common_section_settings(
            $mform,
            $formdata['sectionproperties'],
            $dbformdata['sectionproperties'],
            SEC_TEAM,
            DEFAULT_COMMON_SECTION_PROPERTIES,
            isset($formdata['fromform'])
        );

        $mform->addElement('header', 'moodle', "Team ");

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
                'required' => true,
                'requiredmsg' => get_string('missingdescription', COMPONENT)
            );
        $this->add_section_title_settings($mform, $formdata[$title], $textobj, $title);

        $title = 'view';
        $options = [0 => 'Square', 1 => 'Round'];
        $defaultval = (!isset($dbformdata['view'])) ? 0 : $dbformdata['view'];
        $mform->addElement('select', $title, get_string('view', COMPONENT), $options, array('class' => ' ml-0 mr-5 mb-10 '));
        $mform->setDefault($title, $defaultval);

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

        // Members Setting.
        for ($nrow = 0; $nrow < $formdata['rows']; $nrow++) {
            // As loop starts from 0, but we want to print 1 as Start point, this is to display only.
            $dispnrow = $nrow + 1;

            // Print the required moodle fields first.
            $mform->addElement('header', 'moodle', "Row ".$dispnrow);

            // Members Count Select Dropdown Setting.
            $rowscount = ['1' => 1, '2' => 2, '3' => 3, '4' => 4];
            $defaultval = (!isset($dbformdata['row'][$nrow]['members'])) ?
                          $rowscount['1'] : $dbformdata['row'][$nrow]['members'];
            $defaultval = (!isset($formdata['row'][$nrow]['members'])) ?
                          $defaultval : $formdata['row'][$nrow]['members'];
            $title = 'row'.'_'.$nrow.'_members';
            $mform->addElement(
                'select',
                $title,
                get_string('members', COMPONENT),
                $rowscount,
                array('class' => 'updateform ml-0 mr-5 mb-10 ')
            );
            $mform->setDefault($title, $defaultval);

            for ($nmember = 0; $nmember < $formdata['row'][$nrow]['members']; $nmember++) {
                $dispnmember = $nmember + 1;
                $mform->addElement('header', 'moodle', "Member ".$dispnmember);

                // Name Setting of $nmember'th Member.
                $title = 'row' . '_' . $nrow .'_member_'.$nmember.'_'. 'name';
                $textobj = array(
                    'label' => get_string('name', COMPONENT),
                    'type' => 'text',
                    'placeholder' => get_string('teammembernameplaceholder', COMPONENT),
                    'required' => true,
                    'requiredmsg' => get_string('missingname', COMPONENT),
                    'onlycolor' => true,
                );
                $defaultval = isset($dbformdata['row'][$nrow]['member'][$nmember]['name']) ?
                              $dbformdata['row'][$nrow]['member'][$nmember]['name'] : [];
                $defaultval = isset($formdata['row'][$nrow]['member'][$nmember]['name']) ?
                              $formdata['row'][$nrow]['member'][$nmember]['name'] : $defaultval;
                $this->add_section_title_settings($mform, $defaultval, $textobj, $title);

                // Member Image.
                $title = 'row' . '_' . $nrow .'_member_'.$nmember.'_'.'itemid';

                $draftitemid = null;
                $itemid = null;
                $context = \context_system::instance();
                // This will load image from new config, but check that it is not the same itemid from db and new config.
                // Because at first time, formdata = dbformdata.
                $formitemid = isset($formdata['row'][$nrow]['member'][$nmember]['itemid']) ?
                              $formdata['row'][$nrow]['member'][$nmember]['itemid'] : null;
                $dbitemid = isset($dbformdata['row'][$nrow]['member'][$nmember]['itemid']) ?
                              $dbformdata['row'][$nrow]['member'][$nmember]['itemid'] : null;

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
                        SEC_TEAM,
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

                // Member Quote.
                // Name Setting of $nmember'th Member.
                $title = 'row' . '_' . $nrow .'_member_'.$nmember.'_'.'quote';
                $textobj = array(
                    'label' => get_string('quote', COMPONENT),
                    'type' => 'text',
                    'placeholder' => get_string('teammemberquoteplaceholder', COMPONENT),
                    'required' => false,
                    'onlycolor' => true,
                );
                $defaultval = isset($dbformdata['row'][$nrow]['member'][$nmember]['quote']) ?
                              $dbformdata['row'][$nrow]['member'][$nmember]['quote'] : [];
                $defaultval = isset($formdata['row'][$nrow]['member'][$nmember]['quote']) ?
                              $formdata['row'][$nrow]['member'][$nmember]['quote'] : $defaultval;
                $this->add_section_title_settings($mform, $defaultval, $textobj, $title);
            }
        }
    }

    /**
     * Update team files
     * @param  array $oldconfig Old configuration of section
     * @param  array $newconfig New configuration submitted in the form
     * @return array            Array of section configuration data
     */
    public function update_team_files($oldconfig, $newconfig) {
        // This call to delete the files.
        $this->update_team_file_area($oldconfig, true);
        // This call to save the files.
        return $this->update_team_file_area($newconfig, false);
    }

    /**
     * Update team files in team file area or delete if delete parameter is set
     * @param  array   $configdata Configuration data
     * @param  boolean $delete     true if need to delete from congfigdata
     * @return array               Configuration data
     */
    public function update_team_file_area($configdata, $delete = true) {
        global $CFG, $OUTPUT;
        require_once($CFG->dirroot . "/theme/remui/lib.php");

        $fs = get_file_storage();
        $systemcontext = \context_system::instance();
        foreach ($configdata['row'] as $krow => $vrow) {
            foreach ($configdata['row'][$krow]['member'] as $kmember => $member) {
                $itemid = $member['itemid'];
                if ($itemid != "" || $itemid != null) {
                    if ($delete) {
                        $fs->delete_area_files($systemcontext->id, THEME_COMPONENT, SEC_TEAM, $itemid);
                    } else {
                        $newitemid = theme_remui_get_unused_itemid(SEC_TEAM);
                        file_save_draft_area_files($itemid, $systemcontext->id, THEME_COMPONENT, SEC_TEAM, $newitemid);
                        $configdata['row'][$krow]['member'][$kmember]['itemid'] = $newitemid;
                        $imgurl = get_file_img_url($newitemid, THEME_COMPONENT, SEC_TEAM);
                        if ($imgurl == "") {
                            $imgurl = $OUTPUT->image_url('u/f2')->out();
                        }
                        $configdata['row'][$krow]['member'][$kmember]['imgurl'] = $imgurl;
                        $this->delete_draft_file($itemid);
                    }
                }
            }
        }

        $configdata = $this->update_section_bg_file(SEC_TEAM, $configdata, $delete);

        if ($delete) {
            // While deleting no need to return anything.
            return;
        }

        if (isset($configdata['view']) && $configdata['view'] == 0) {
            unset($configdata['view']);
        }

        // Need to save updated configdata, that is why returning here.
        return $configdata;
    }

    /**
     * Create copy of all files used in team for draft config
     * @param  array $configdata draft config data array
     * @return array             updated draft config
     */
    private function team_duplicate_file_in_config($configdata) {
        global $CFG, $OUTPUT;

        $context = context_system::instance();
        foreach ($configdata['row'] as $krow => $vrow) {
            foreach ($configdata['row'][$krow]['member'] as $kmember => $member) {
                $itemid = $member['itemid'];
                if ($itemid != "" || $itemid != null) {
                    $draftitemid = null;
                    file_prepare_draft_area(
                        $draftitemid,
                        $context->id,
                        THEME_COMPONENT,
                        SEC_TEAM,
                        $itemid,
                        array(
                            'subdirs' => 0,
                            'maxfiles' => 1
                        )
                    );
                    $configdata['row'][$krow]['member'][$kmember]['itemid'] = $draftitemid;
                }
            }
        }

        $configdata = $this->duplicate_section_bg_file(SEC_TEAM, $configdata);

        return $this->update_team_file_area($configdata, false);
    }

    /**
     * Create team member images before saving first instance.
     * @param  int    $id       instance id
     * @param  array  $configdata Default section data
     * @return array            updated form data
     */
    private function team_process_section_creation($id, $configdata) {
        global $CFG, $USER;
        $fs = get_file_storage();
        foreach ($configdata['row'] as $rownum => $row) {
            foreach ($row['member'] as $memnum => $member) {
                $img = $member['imgurl'];
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
                $member['itemid'] = $itemid;
                $row['member'][$memnum] = $member;
            }
            $configdata['row'][$rownum] = $row;
        }
        return $this->update_team_file_area($configdata, false);
    }
}
