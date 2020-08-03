<?php
error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/../../config.php');

use block_xp\local\xp\level_with_name;
use core_completion\progress;

try {
	$details = $_POST;
	$returnArr = array();

	if (!isset($_REQUEST['request_type']) || strlen($_REQUEST['request_type']) == false) {
		throw new Exception();
	}

	switch ($_REQUEST['request_type']) {
		case 'obtenerUsuario':
			$returnArr = obtenerUsuario();
			break;
		case 'obtenerCursos':
			$returnArr = obtenerCursos();
			break;
		case 'obtenerNivel':
			$returnArr = obtenerNivel();
			break;
		case 'grabarMillas':
			$returnArr = grabarMillas();
			break;
		case 'obtenerUltimasMillasGanadas':
			$returnArr = obtenerUltimasMillasGanadas();
			break;
	}
} catch (Exception $e) {
	$returnArr['status'] = false;
	$returnArr['data'] = $e->getMessage();
}

header('Content-type: application/json');

echo json_encode($returnArr);
exit();

function obtenerUsuario() {
	global $USER;
	$response['status'] = true;
	$response['data'] = json_encode($USER, JSON_PRETTY_PRINT);

	return $response;
}

function obtenerCursos() {
	global $DB, $USER;

	$courses = obtenerCursosRaw();
	$finalizado = 0;

	foreach ($courses as $id=>$course) {
		//samu - prod - if not inducción
		if($course->category != 9) {
			continue;
		}

		$percentage = progress::get_course_progress_percentage($course, $USER->id);

		if($percentage == 100) {
			$finalizado = 1;
		}

		$category = $DB->get_record('course_categories',array('id'=>$course->category));

		$courseDetail['courseId'] = $course->id;
		$courseDetail['courseShortName'] = $course->shortname;
		$courseDetail['courseFullName'] = $course->fullname;
		$courseDetail['isVisible'] = $course->visible;
		$courseDetail['categoryName'] = $category->name;
		$courseDetail['percentage'] = round($percentage);
		$courseDetail['isCompleted'] = $finalizado;
		$courseDetail['URL'] = obtenerURLCurso($course->id);
		$allCourses[$id] = $courseDetail;
	}

	$response['status'] = true;
	$response['data'] = json_encode($allCourses, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

	return $response;
}

function obtenerNivel() {
	global $USER;

	$courses = obtenerCursosRaw();
	$courseId = $courses[0]->id;

	$xpRenderer = \block_xp\di::get('renderer');
	$world = \block_xp\di::get('course_world_factory')->get_world($courseId);
	$state = $world->get_store()->get_state($USER->id);
	$widget = new \block_xp\output\xp_widget($state, [], null, []);
	$level = $widget->state->get_level();

	//Get data
	$levelName = obtenerLevelPropertyValue($level, 'name');
	$xp = $widget->state->get_xp();

	$levelInfo = array('levelName' => $levelName, 'xp' =>$xp);

	$response['status'] = true;
	$response['data'] = json_encode($levelInfo, JSON_PRETTY_PRINT);

	return $response;
}

function grabarMillas() {
	global $DB, $details;

	$data['courseid'] = $details['cursoId'];
	$data['userid'] = $details['userId'];
	$data['xp'] = $details['xp'];
	$data['eventname'] = 'earned_miles';
	$data['time'] = time();

	$DB->insert_record('block_xp_log', $data);

	$response['status'] = true;

	return $response;
}

function obtenerUltimasMillasGanadas() {
	global $DB, $details;

	$xpLogObj = $DB->get_record_sql("SELECT * FROM {block_xp_log} WHERE courseid = ? AND userid = ? AND eventname = ? ORDER BY id DESC", array($details['cursoIdGet'], $details['userIdGet'], 'earned_miles'));

	$response['status'] = true;
	$response['data'] = $xpLogObj->xp;
	return $response;
}

/** HELPERS **/

function obtenerCursosRaw() {
	return get_courses();
}

function obtenerURLCurso($courseId) {
	$urlObj = new moodle_url("/course/view.php",array("id" => $courseId));
	return $urlObj->out();
}

function obtenerLevelPropertyValue($level, $property) {
	$returnedValue = '';

	switch($property) {
		case 'name':
			$name = $level instanceof level_with_name ? $level->get_name() : null;
			if (empty($name)) {
				$name = get_string('levelx', 'block_xp', $level->get_level());
			}
			$returnedValue = $name;
			break;
	}
	return $returnedValue;
}