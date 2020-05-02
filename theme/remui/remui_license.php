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
 * Edwiser RemUI License
 * @package    theme_remui
 * @copyright  (c) 2018 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// include Moodle config
if (!@include_once(__DIR__ . '/../../config.php')) {
    include_once('/var/www/remui.local/html/v38/config.php');
}

global $DB, $PAGE;


// shhhhhh... non loggedin or non admin user
// Users must log in and can not be guests.
require_login();
$systemcontext = context_system::instance();
$PAGE->set_context($systemcontext);
if(!is_siteadmin()) {
    exit('go away sir...');
}


// license controller
$l_controller = new \theme_remui\controller\license_controller();
$plugin_slug = 'remui';

// Get License key
$license_key = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'edd_' . $plugin_slug . '_license_key'), IGNORE_MISSING);


// Get License Status
$status = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'edd_' . $plugin_slug . '_license_status'), IGNORE_MISSING);


// Get renew link
$renew_link = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'wdm_' . $plugin_slug . '_product_site'), IGNORE_MISSING);
$licensekey = \theme_remui\toolbox::get_setting('licensekey');
$licensekeyactivate = \theme_remui\toolbox::get_setting('licensekeyactivate');
$licensekeydeactivate = \theme_remui\toolbox::get_setting('licensekeydeactivate');
$get_trans = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'wdm_' . $plugin_slug . '_license_trans'), IGNORE_MISSING);
$get_trans = unserialize($get_trans);

//Show proper reponse to user on license activation/deactivation
if ($licensekey == 'empty') {
    //If empty, show error message
    echo '<div class="alert alert-danger">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       <h4><i class="icon fa fa-ban"></i> Error</h4>' . get_string("enterlicensekey", "theme_remui") . '
    </div>';
} elseif (isset($get_trans[0]) && $get_trans[0] == "server_did_not_respond") {
    echo '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> Alert!</h4>' . get_string("noresponsereceived", "theme_remui") . '
            </div>';
} elseif (!empty($licensekey) && $licensekey != 'empty') {
    if ($status !== false && $status == 'valid' && !empty($licensekeyactivate)) {
        //Valid license key
        echo '<div class="alert alert-success">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
           <h4><i class="icon fa fa-check"></i> Success</h4>' . get_string("licensekeyactivated", "theme_remui") . '
        </div>';
    } elseif ($status !== false && $status == 'expired') { //Expired license key
        echo '<div class="alert alert-danger">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       <h4><i class="icon fa fa-ban"></i> Alert!</h4>' . get_string("licensekeyhasexpired", "theme_remui") . '
    </div>';
    } elseif ($status !== false && $status == 'disabled') { //Disabled license key
        echo '<div class="alert alert-danger">
               <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
               <h4><i class="icon fa fa-ban"></i> Alert!</h4>' . get_string("licensekeyisdisabled", "theme_remui") . '
            </div>';
    } elseif ($status == 'invalid') { //Invalid license key
        echo '<div class="alert alert-danger">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       <h4><i class="icon fa fa-ban"></i> Alert!</h4>' . get_string("entervalidlicensekey", "theme_remui") . '
    </div>';
    } elseif ($status == 'no_activations_left') { //No activation limit left.
        echo '<div class="alert alert-danger">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       <h4><i class="icon fa fa-ban"></i> Alert!</h4>' . get_string("nolicenselimitleft", "theme_remui") . '
    </div>';
    } elseif ($status == 'site_inactive') { //Site is inactive
        echo '<div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-ban"></i> Alert!</h4>' . get_string("siteinactive", "theme_remui") . '
            </div>';
    } elseif ($status == 'deactivated') { //Site is inactive
        echo '<div class="alert alert-danger">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       <h4><i class="icon fa fa-ban"></i> Alert!</h4>' . get_string("licensekeydeactivated", "theme_remui") . '
       </div>';
    } elseif ($status == 'no_response' || (!empty($licensekeydeactivate) && $status == 'valid') || (isset($get_trans[0]) && $get_trans[0] == "server_did_not_respond")) { //Site is inactive
        echo '<div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-ban"></i> Alert!</h4>' . get_string("noresponsereceived", "theme_remui") . '
            </div>';
    }
}

// remove config vars
unset_config('licensekey', 'theme_remui');
unset_config('licensekeyactivate', 'theme_remui');
unset_config('licensekeydeactivate', 'theme_remui');
?>
<div class="license-box box box-warning">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo get_string('edwiserremuilicenseactivation', 'theme_remui') ?></h3>
    </div>

    <!-- /.box-header -->
    <div class="panel-body">

        <input type="hidden" name="activetab" value="remuilicensestatus">

        <?php
        if ($status == "valid") {
            ?>

            <div class="form-group">

                <label class="control-label text-black col-sm-3"><?php echo get_string('licensekey', 'theme_remui') ?>:</label>

                <div class="col-sm-9">
    <?php echo "<input id='edd_{$plugin_slug}_license_key' class='form-control is-valid' name='edd_{$plugin_slug}_license_key' type='text' class='regular-text' value='{$license_key}' placeholder='Enter license key...' readonly/>"; ?>
                </div>
            </div>

    <?php
} elseif ($status == "expired") {
    ?>

            <div class="form-group">

                <label class="control-label text-black col-sm-3"><?php echo get_string('licensekey', 'theme_remui') ?>:</label>

                <div class="col-sm-9">
    <?php echo "<input id='edd_{$plugin_slug}_license_key' class='form-control is-invalid' name='edd_{$plugin_slug}_license_key' type='text' class='regular-text' value='{$license_key}' placeholder='Enter license key...' readonly/>"; ?>
                </div>
            </div>

    <?php
} else {
    ?>

            <div class="form-group">

                <label class="control-label text-black col-sm-3"><?php echo get_string('licensekey', 'theme_remui') ?>:</label>

                <div class="col-sm-9">
    <?php echo "<input id='edd_{$plugin_slug}_license_key' class='form-control is-invalid'  name='edd_{$plugin_slug}_license_key' type='text' class='regular-text' value='{$license_key}' placeholder='Enter license key...' />"; ?>
                </div>
            </div>

    <?php }
?>

        <div class="form-group">
<?php
echo '<label class="control-label col-sm-3">' . get_string('licensestatus', 'theme_remui') . ':</label>';

echo '<div class="col-sm-9">';

$status_text_active = get_string('active', 'theme_remui');
$status_text_active_text = "<p style='color:green;'>{$status_text_active}</p>";
$status_text_inactive = get_string('notactive', 'theme_remui');
$status_text_inactive_text = "<p style='color:red;'>{$status_text_inactive}</p>";
$status_text_expired = get_string('expired', 'theme_remui');
$status_text_expired_text = "<p style='color:red;'>{$status_text_expired}</p>";

if ($status !== false && $status == 'valid') {
    echo $status_text_active_text;
} elseif ($status == 'site_inactive') {
    echo $status_text_inactive_text;
} elseif ($status == 'expired') {
    echo $status_text_expired_text;
} elseif ($status == 'invalid') {
    echo $status_text_inactive_text;
} else {
    echo $status_text_inactive_text;
}

echo '</div>';
?>
        </div>

        <div class="form-group">
<?php
$activate_license_text = get_string('activatelicense', 'theme_remui');
$deactivate_license_text = get_string('deactivatelicense', 'theme_remui');
$renew_license_text = get_string('renewlicense', 'theme_remui');

echo '<div class="col-sm-9">';

// hidden field to cehck if on license tab
echo "<input type='hidden' id='onLicensePage' name='onLicensePage' value='0'/>";

if ($status !== false && $status == 'valid') {
    echo "<input type='submit' class='btn btn-primary text-white'  style='color:white;' name='edd_{$plugin_slug}_license_deactivate' value='{$deactivate_license_text}'/>";
} elseif ($status == 'expired') {
    echo "<input type='submit' class='btn btn-primary' style='color:white;' name='edd_{$plugin_slug}_license_deactivate' value='{$deactivate_license_text}'/>&nbsp&nbsp";

    echo '<input type="button" class="btn btn-primary" style="color:white;" name="edd_' . $plugin_slug . '_license_renew" value="' . $renew_license_text . '" onclick="window.open(\'' . $renew_link . '\');">';
} else {
    echo "<input type='submit' class='btn btn-primary' style='color:white;' name='edd_{$plugin_slug}_license_activate' value='{$activate_license_text}'/>";
}

echo '</div>';
?>
        </div>

    </div>
    <!-- /.box-body -->
</div>
