<?php
require_once(dirname(__FILE__) . '/../../config.php');

require_login();

$title = 'Terminologia';
// Set up the page.
$url = new moodle_url("/local/gameapp/index.php", array('component' => $component, 'search' => $search));
$PAGE->set_url($url);

$PAGE->requires->jquery();
$PAGE->requires->js(new moodle_url('js/gameapp.js'));

echo $OUTPUT->header();

echo
'<h3> API para obtener información de usuario </h3>
<label> Seleccionar request </label>
<select id="request_name" name="request_name">
<option value="obtenerUsuario">Obtener usuario</option>
<option value="obtenerCursos">Obtener cursos</option>
<option value="obtenerNivel">Obtener nivel (millas)</option>
</select>
<input type="button" id="procesar" value="Procesar">
<br/>
Resultados:
<br/>
<textarea id="results" style="margin: 0; height: 301px; width: 880px;"></textarea>';

echo $OUTPUT->footer();