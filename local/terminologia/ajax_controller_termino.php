<?php
error_reporting(E_ALL);
ini_set("display_errors","on");

require_once '../terminologia/classes/controller/Termino.php';

use LocalPages\Controller\Termino as TerminoController;

try {

	$details = $_POST;
	$returnArr = array();

	if (!isset($_REQUEST['request_type']) || strlen($_REQUEST['request_type']) == false) {
		throw new Exception();
	}

	switch ($_REQUEST['request_type']) {
		case 'get_termino':
			$returnArr = get_termino();
			break;
		case 'get_termino_detail':
			$returnArr = get_termino_detail();
			break;
		case 'add_termino':
			$returnArr = add_termino();
			break;
		case 'edit_termino':
			$returnArr = edit_termino();
			break;
		case 'delete_termino':
			$returnArr = delete_termino();
			break;
		case 'get_sugerencias_all':
			$returnArr = get_sugerencias_all();
			break;
		case 'add_sugerencia':
			$returnArr = add_sugerencia();
			break;
		case 'delete_sugerencia':
			$returnArr = delete_sugerencia();
			break;
		case 'get_termino_busqueda':
			$returnArr = get_termino_busqueda();
			break;
		case 'add_termino_visit':
			$returnArr = add_termino_visit();
			break;
	}
} catch (Exception $e) {
	$returnArr['status'] = false;
	$returnArr['data'] = $e->getMessage();
}

header('Content-type: application/json');
echo json_encode($returnArr);
exit();

function get_termino() {
	global $details;
	$terminoController = new TerminoController();
	$terminoArr = $terminoController->ObtenerTerminoPorLetra($details['letra']);
	$response['status'] = true;
	$response['data'] = $terminoArr;

	return $response;
}

function get_termino_detail() {
	global $details;
	$terminoController = new TerminoController();
	$terminoArr = $terminoController->ObtenerTerminoPorId($details['id']);
	$response['status'] = true;
	$response['data'] = $terminoArr;

	return $response;
}

function add_termino() {
	global $details;

	$terminoController = new TerminoController();
	$response['id'] = $terminoController->AgregarTermino($details, $_FILES);

	return $response;
}

function edit_termino() {
	global $details;

	$terminoController = new TerminoController();
	$response['status'] = $terminoController->EditarTermino($details, $_FILES);

	return $response;
}

function delete_termino() {
	global $details;

	$terminoController = new TerminoController();

	$response['status'] = $terminoController->EliminarTermino(intval($details['id']));

	return $response;
}

function get_sugerencias_all() {
	global $details;

	$terminoController = new TerminoController();
	$response['data'] = $terminoController->GetSugerencias();

	return $response;
}

function add_sugerencia() {
	global $details;

	$terminoController = new TerminoController();
	$response['status'] = $terminoController->AgregarSugerencia($details['termino-sugerido']);

	return $response;
}

function delete_sugerencia() {
	global $details;

	$terminoController = new TerminoController();
	$response['status'] = $terminoController->EliminarSugerencia($details['id']);

	return $response;
}

function get_termino_busqueda() {
	global $details;
	$response['data'] = '';

	if(empty($details['keyword'])) {
		return $response;
	}

	$terminoController = new TerminoController();
	$result = $terminoController->GetTerminoBusqueda($details['keyword']);

	if(!empty($result)) {
		$response['data'] = ' <div class="dropdown-menu search-dropdown">';

		foreach($result as $res) {
			$response['data'] .= '<a class="dropdown-item result-search-element" termino="' . $res->nombre . '" id="' . $res->id . '">'. $res->nombre .'</a>';
		}

		$response['data'] .=  '</div>';
	}

	return $response;
}

function add_termino_visit() {
	global $details;

	$terminoController = new TerminoController();
	$response['status'] = $terminoController->AgregarTerminoVisita($details['id']);

	return $response;
}