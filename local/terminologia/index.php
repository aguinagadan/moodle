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

$PAGE->requires->css(new moodle_url('css/terminologia.css'));

$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url('js/terminologia_base.js'));
$PAGE->requires->js(new moodle_url('js/terminologia_admin.js'));
$PAGE->requires->js(new moodle_url('js/terminologia_guest.js'));

echo $OUTPUT->header();

include('term_base.php');

if($isManager) {
	include('term_admin.php');
} else {
	include('term_guest.php');
}

echo $OUTPUT->footer();