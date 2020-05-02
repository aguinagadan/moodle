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
 * Edwiser RemUI
 * @package    theme_remui
 * @copyright  (c) 2018 WisdmLabs (https://wisdmlabs.com/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_remui\controller;

use moodle_url;
use context_system;
use stdClass;

if (!class_exists('license_controller')) {
    class license_controller
    {

        /**
         *
         * @var string Short Name for plugin.
         */
        private $plugin_short_name = '';

        /**
         *
         * @var string Slug to be used in url and functions name
         */
        private $plugin_slug = '';

        /**
         *
         * @var string stores the current plugin version
         */
        private $plugin_version = '';

        /**
         *
         * @var string Handles the plugin name
         */
        private $plugin_name = '';

        /**
         *
         * @var string  Stores the URL of store. Retrieves updates from
         *              this store
         */
        private $store_url = '';

        /**
         *
         * @var string  Name of the Author
         */
        private $author_name = '';
        public static $responseData;

        /**
         * Developer Note: This variable is used everywhere to check license information and verify the data.
         * Change the Name of this variable in this file wherever it appears and also remove this comment
         * After you are done with adding Licensing
         */
        public $wdm_remui_data = array(
            'plugin_short_name' => 'Edwiser RemUI', //Plugins short name appears on the License Menu Page
            'plugin_slug' => 'remui', //this slug is used to store the data in db. License is checked using two options viz edd_<slug>_license_key and edd_<slug>_license_status
            'plugin_version' => '3.3.0', //Current Version of the plugin. This should be similar to Version tag mentioned in Plugin headers
            'plugin_name' => 'Edwiser RemUI', //Under this Name product should be created on WisdmLabs Site
            'store_url' => 'https://edwiser.org/check-update', //Url where program pings to check if update is available and license validity
            'author_name' => 'WisdmLabs', //Author Name
        );

        public function __construct()
        {
            $this->author_name = $this->wdm_remui_data['author_name'];
            $this->plugin_name = $this->wdm_remui_data['plugin_name'];
            $this->plugin_short_name = $this->wdm_remui_data['plugin_short_name'];
            $this->plugin_slug = $this->wdm_remui_data['plugin_slug'];
            $this->plugin_version = $this->wdm_remui_data['plugin_version'];
            $this->store_url = $this->wdm_remui_data['store_url'];
        }

        /**
         * The function send the request to the edwiser.org,
         * To perform the activation deactivation actions on it.
         *
         *
         * @param String $action name of the action it can be activate_license, deactivate_license or
         * @param String $license_key license key to perform the action
         * @return Mix return false if request fails otherwise the requests response is return
         */
        private function requestLicenseData($action, $license_key)
        {
            global $CFG;
            // Get cURL resource
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $this->store_url,
                CURLOPT_POST => 1,
                CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'] . ' - ' . $CFG->wwwroot,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => array(
                    'edd_action' => $action,
                    'license' => $license_key,
                    'item_name' => urlencode($this->plugin_name),
                    'current_version' => $this->plugin_version,
                    'url' => urlencode($CFG->wwwroot),
                )
            ));
            /**
             * Since edwiser.org dose not accept request from the IPv6 address to solve that problem,
             * Try to send request using IPv4 address.
             */
            if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
                curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            }

            // Send the request & save response to $resp.
            $resp = curl_exec($curl);
            $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            // Close request to clear up some resources
            curl_close($curl);

            try {
                $license_data = json_decode($resp);
            } catch (Exception $ex) {
                $license_data = false;
            }
            $is_valid_response = $this->validateResponse($license_data, $response_code);

            if ($is_valid_response == false) {
                return false;
            } else {
                return $license_data;
            }
        }

        /**
         * Checks is the request response is valid or not.
         *
         * @param JSOn Object $license_data
         * @param Number $response_code
         * @param Array $valid_response_code
         * @return boolean returns true if the license data is not null and contains the valid responce code
         */
        public function validateResponse($license_data, $response_code)
        {
            $valid_response_code = array('200', '301');
            $is_valid = true;
            if ($license_data == null || !in_array($response_code, $valid_response_code)) {
                $GLOBALS['wdm_server_null_response'] = true;
                //calculate transiaent period from current period to request.
                $trans_period = serialize(array('server_did_not_respond', time() + (60 * 60 * 24)));
                // delete previous record of the transiaent and set new transient to check the license after 24 hrs.
                $this->setPluginConfig('wdm_' . $this->plugin_slug . '_license_trans', $trans_period);
                $is_valid = false;
            }
            return $is_valid;
        }

        /**
         * The function provides the functionality to set the plugin configuration,
         * Since in some cases records are getting created new or existing record gets updated.
         * So the function first deletes the existing record and then it adds new record in plugin configuration.
         *
         *
         * @global type $DB Database object
         * @param String $config_name configuration name
         * @param String $config_value configuration value
         */
        private function setPluginConfig($config_name, $config_value)
        {
            global $DB;
            try {
                //Delete old record
                $DB->delete_records_select('config_plugins', 'name = :name', array('name' => $config_name));
            } catch (dml_exception $e) {
                // keep catch empty if no record found
            }
            $dataobject = new stdClass();
            $dataobject->plugin = 'theme_remui';
            $dataobject->name = $config_name;
            $dataobject->value = $config_value;
            //Add new record.
            $DB->insert_record('config_plugins', $dataobject);
        }

        /**
         * The function parses the response come from the edwiser.org
         * on activation and determines is status of the license key.
         *
         *
         * @param JSON Object $license_data the response retune by the activation request.
         * @return String returns the license key status
         */
        public function statusUpdate($license_data)
        {
            $status = "";
            if ((empty($license_data->success)) && isset($license_data->error) && ($license_data->error == "expired")) {
                $status = 'expired';
            } elseif ($license_data->license == 'invalid' && isset($license_data->error) && $license_data->error == "disabled") {
                $status = 'disabled';
            } elseif ($license_data->license == 'invalid' && isset($license_data->error) && $license_data->error == "no_activations_left") {
                $status = 'no_activations_left';
            } elseif ($license_data->license == 'invalid' && $license_data->activations_left == "0") {
                $status = 'invalid';
            } elseif ($license_data->license == 'failed') {
                $status = 'failed';
                $GLOBALS['wdm_license_activation_failed'] = true;
            } else {
                $status = $license_data->license;
            }
            $this->setPluginConfig('edd_' . $this->plugin_slug . '_license_status', $status);
            return $status;
        }

        public function activateLicense()
        {
            $license_key = trim($_POST['edd_' . $this->plugin_slug . '_license_key']);
            if ($license_key) {
                // delete previous license key from plugin configration add new key.
                $config_name = 'edd_' . $this->plugin_slug . '_license_key';
                $this->setPluginConfig($config_name, $license_key);

                $license_data = $this->requestLicenseData('activate_license', $license_key);
                if (false === $license_data) {
                    return;
                }

                $exp_time = 0;
                if (isset($license_data->expires)) {
                    $exp_time = strtotime($license_data->expires);
                }
                $cur_time = time();

                if (isset($license_data->expires) && ($license_data->expires !== false) && ($license_data->expires != 'lifetime') && $exp_time <= $cur_time && $exp_time != 0) {
                    $license_data->error = (isset($license_data->error) && "no_activations_left" === $license_data->error) ? $license_data->error : "expired";
                }

                if (isset($license_data->renew_link) && (!empty($license_data->renew_link) || $license_data->renew_link != "")) {
                    //If the license key's validity is expired then save the renew link for the product.
                    $this->setPluginConfig('wdm_' . $this->plugin_slug . '_product_site', $license_data->renew_link);
                } else {
                    $this->setPluginConfig('wdm_' . $this->plugin_slug . '_product_site', "https://edwiser.org");
                }
                $license_status = $this->statusUpdate($license_data);
                $this->setTransientOnActivation($license_status);
            }
        }

        public function setTransientOnActivation($license_status)
        {
            global $DB;

            $trans_expired = false;

            // Get license trans
            $trans_var = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'wdm_' . $this->plugin_slug . '_license_trans'), IGNORE_MISSING);
            // check license trans is  expired
            if ($trans_var) {
                $trans_var = unserialize($trans_var);
                if (is_array($trans_var) && time() > $trans_var[1] && $trans_var[1] > 0) {
                    $trans_expired = true;
                }
            } else {
                $trans_expired = true;
            }

            if ($trans_expired == true) {
                if (!empty($license_status)) {
                    $time = time() + (60 * 60 * 24) * (($license_status == 'valid') ? 7 : 1);
                    $this->setPluginConfig('wdm_' . $this->plugin_slug . '_license_trans', serialize(array($license_status, $time)));
                } else {
                    // delete previous license trans
                    try {
                        $DB->delete_records_select('config_plugins', 'name = :name', array('name' => 'wdm_' . $this->plugin_slug . '_license_trans'));
                    } catch (dml_exception $e) {
                        // keep catch empty if no record found
                    }
                }
            }
        }

        public function deactivateLicense()
        {
            global $DB;

            $wpep_license_key = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'edd_' . $this->plugin_slug . '_license_key'), IGNORE_MISSING);

            if (!empty($wpep_license_key)) {
                $license_data = $this->requestLicenseData("deactivate_license", $wpep_license_key);

                if ($license_data == false) {
                    return;
                }

                if ($license_data->license == 'deactivated' || $license_data->license == 'failed') {
                    // Set the licesnign status to the deactivated.
                    $this->setPluginConfig('edd_' . $this->plugin_slug . '_license_status', 'deactivated');
                }
                // Set linces check transient value on deactivation to 0.
                $this->setPluginConfig('wdm_' . $this->plugin_slug . '_license_trans', serialize(array($license_data->license, 0)));
            }
        }

        public function addData()
        {
            if (is_siteadmin()) {

                // return if did not come from license page.
                if (!isset($_POST['onLicensePage']) || $_POST['onLicensePage'] == 0) {
                    return;
                }

                if (empty(@$_POST['edd_' . $this->plugin_slug . '_license_key'])) {
                    $lk = 'empty';
                } else {
                    $lk = @$_POST['edd_' . $this->plugin_slug . '_license_key'];
                }
                if (isset($_POST['edd_' . $this->plugin_slug . '_license_activate'])) {

                    // jugad to tackle the page redirect after save license
                    set_config('licensekey', $lk, 'theme_remui');
                    set_config('licensekeyactivate', @$_POST['edd_' . $this->plugin_slug . '_license_activate'], 'theme_remui');

                    return $this->activateLicense();
                } elseif (isset($_POST['edd_' . $this->plugin_slug . '_license_deactivate'])) {

                    // jugad to tackle the page redirect after save license
                    set_config('licensekey', $lk, 'theme_remui');
                    set_config('licensekeydeactivate', @$_POST['edd_' . $this->plugin_slug . '_license_deactivate'], 'theme_remui');
                    return $this->deactivateLicense();
                }
            }
        }

        public function getDataFromDb()
        {
            global $DB;
            if (null !== self::$responseData) {
                return self::$responseData;
            }
            $trans_expired = false;
            $update_trans = false;
            $license_status = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'edd_' . $this->plugin_slug . '_license_status'), IGNORE_MISSING);
            $get_trans = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'wdm_' . $this->plugin_slug . '_license_trans'), IGNORE_MISSING);
            if ($get_trans) {
                $get_trans = unserialize($get_trans);

                if (is_array($get_trans) && time() > $get_trans[1] && $get_trans[1] > 0) {
                    $trans_expired = true;
                    // delete previous license trans
                    try {
                        $DB->delete_records_select('config_plugins', 'name = :name', array('name' => 'wdm_' . $this->plugin_slug . '_license_trans'));
                    } catch (dml_exception $e) {
                        // keep catch empty if no record found
                    }
                }
            } else {
                $trans_expired = true;
            }


            if ($trans_expired == true) {
                $license_key = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'edd_' . $this->plugin_slug . '_license_key'), IGNORE_MISSING);

                if ($license_key) {
                    $license_data = $this->requestLicenseData("check_license", $license_key);
                    if (isset($license_data->license) && !empty($license_data->license)) {
                        $license_status = $license_data->license;
                        $this->setPluginConfig('edd_' . $this->plugin_slug . '_license_status', $license_status);
                    }

                    if (empty($license_status)) {
                        return;
                    }

                    $update_trans = true;
                }
            }
            $this->setResponseData($license_status, $this->plugin_slug, $update_trans);
            return self::$responseData;
        }

        public function setResponseData($license_status, $plugin_slug, $set_transient = false)
        {
            if ($license_status == 'valid') {
                self::$responseData = 'available';
            } elseif ($license_status == 'expired') {
                self::$responseData = 'available';
            } else {
                self::$responseData = 'unavailable';
            }

            if ($set_transient) {
                $time = time() + (60 * 60 * 24) * (($license_status == 'valid') ? 7 : 1);
                $transiantPeriod = serialize(array($license_status, $time));
                // Set license status check transient to seven days.
                $this->setPluginConfig('wdm_' . $plugin_slug . '_license_trans', $transiantPeriod);
            }
        }

        /**
         * This function is used to get list of sites where license key is already acvtivated.
         *
         * @param type $plugin_slug current plugin's slug
         * @return string  list of site
         *
         *
         */
        public function getSiteList()
        {
            global $DB, $CFG;

            //$sites       = get_option('wdm_' . $plugin_slug . '_license_key_sites');
            //$max         = get_option('wdm_' . $plugin_slug . '_license_max_site');

            $sites = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'wdm_' . $this->plugin_slug . '_license_key_sites'), IGNORE_MISSING);

            $max = $DB->get_field_select('config_plugins', 'value', 'name = :name', array('name' => 'wdm_' . $this->plugin_slug . '_license_max_site'), IGNORE_MISSING);

            $sites = unserialize($sites);

            $cur_site = $CFG->wwwroot;
            $cur_site = preg_replace('#^https?://#', '', $cur_site);

            $site_count = 0;
            $active_site = "";

            if (!empty($sites) || $sites != "") {
                foreach ($sites as $key) {
                    foreach ($key as $value) {
                        $value = rtrim($value, "/");

                        if (strcasecmp($value, $cur_site) != 0) {
                            $active_site .= "<li>" . $value . "</li>";
                            $site_count ++;
                        }
                    }
                }
            }

            if ($site_count >= $max) {
                return $active_site;
            } else {
                return "";
            }
        }
    }
}
