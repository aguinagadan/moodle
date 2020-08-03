<?php
require_once(dirname(__FILE__) . '/../../config.php');

require_login();

$title = 'Terminologia';
// Set up the page.
$url = new moodle_url("/local/induccion/index.php", array('component' => $component, 'search' => $search));
$PAGE->set_url($url);

$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url('js/induccion.js'));

echo $OUTPUT->header();

echo '
<div class="row">
	<div class="col-sm">
		<h3> API para grabar millas ganadas </h3>
		<label>Curso ID</label><input class="w-25" type="text" name="cursoId" id="cursoId">
		<br/>
		<label>User ID</label><input class="w-25" type="text" name="userId" id="userId">
		<br/>
		<label>Cantidad de millas ganadas</label><input class="w-25" type="text" name="xp" id="xp">
		<br/>
		<input type="button" id="procesarMillas" value="Grabar Millas">
		<label id="procesarMillasRes"></label>
		<br/><br/>
	</div>
	<div class="col-sm">
		<h3> API para obtener ultimas millas ganadas </h3>
			<label>Curso ID</label><input class="w-25" type="text" name="cursoIdGet" id="cursoIdGet">
			<br/>
			<label>User ID</label><input class="w-25" type="text" name="userIdGet" id="userIdGet">
			<br/>
			<input type="button" id="obtenerMillas" value="Obtener Millas"><br/><br/>
	</div>
	<div class="col-sm">
			<label>Resultados: </label>
			<input id="resultsMiles" class="w-25" type="text">
	</div>
</div>';

echo
'<h3> API para obtener información de usuario </h3>
<label> Seleccionar request </label>
<select id="request_name" name="request_name">
<option value="obtenerUsuario">Obtener usuario</option>
<option value="obtenerCursos">Obtener cursos (Solo inducción)</option>
<option value="obtenerNivel">Obtener nivel (millas)</option>
</select>
<input type="button" id="procesar" value="Procesar">
<br/>
Resultados:
<br/>
<textarea id="results" style="margin: 0; height: 301px; width: 880px;"></textarea>';

echo $OUTPUT->footer();