<?php

require_once 'classes/controller/Termino.php';
require_once(dirname(__FILE__) . '/../../config.php');
use LocalPages\Controller\Termino as TerminoController;
error_reporting(E_ALL);

$terminoController = new TerminoController();

$isManager = 1;
$personalcontext = context_user::instance($USER->id);
if (!has_capability('tool/policy:managedocs', $personalcontext)) {
	$isManager = 0;
}

$title = 'Terminologia';
// Set up the page.
$url = new moodle_url("/local/terminologia/index.php", array('component' => $component, 'search' => $search));
$PAGE->set_url($url);

echo $OUTPUT->header();

include('term_base.php');

if($isManager) {
	include('term_admin.php');
} else {
	include('term_guest.php');
}

echo $OUTPUT->footer();