<?php
require_login();

global $terminoController;
$sugerenciasArr = $terminoController->GetSugerencias();
$alphabetArr = range('A', 'Z');

function formatCurrentTime($time) {
	if (isset($time)) {
		$time =
			strtotime(date('Y-m-d H:i', $time));
	}
	return $time;
}

function timeSince($original) {
	$original = formatCurrentTime($original);

	$ta = array(
		array(31536000, "Año", "Años"),
		array(2592000, "Mes", "Meses"),
		array(604800, "Semana", "Semanas"),
		array(86400, "Día", "Días"),
		array(3600, "Hora", "Horas"),
		array(60, "Minuto", "Minutos"),
		array(1, "Segundo", "Segundos")
	);
	$since = time() - $original;
	$res = "";
	$lastkey = 0;
	for ($i = 0; $i < count($ta); $i++) {
		$cnt = floor($since / $ta[$i][0]);
		if ($cnt != 0) {
			$since = $since - ($ta[$i][0] * $cnt);
			if ($res == "") {
				$res .= ($cnt == 1) ? "1 {$ta[$i][1]}" : "{$cnt} {$ta[$i][2]}";
				$lastkey = $i;
			} else if ($ta[0] >= 60 && ($i - $lastkey) == 1) {
				$res .= ($cnt == 1) ? " y 1 {$ta[$i][1]}" : " y {$cnt} {$ta[$i][2]}";
				break;
			} else {
				break;
			}
		}
	}
	return $res;
}

?>
<div class="main-page-container-admin">
    <div class="terminos-sugeridos-container">
        <div class="big-title">
            Términos sugeridos por los usuarios
        </div>
        <div class="terminos-sugeridos-list">
            <div class="list-group terminos-sugeridos-list">
                <table>
                        <?php
                        foreach($sugerenciasArr as $s) {
                            ?> <tr class="sugerencia-row">
                                <td style="border: solid 0.5px #e1d9d9;">
                                  <a type="button" nombre-sugerencia="<?php echo $s->nombre; ?>" class="list-group-item sugeridos-list-item clearfix" style="height: 40px; font-weight: bold; border: 0;text-align: left;"> <?php echo $s->nombre.'    ';?>
                                      <label type="button" style="font-weight: normal;margin-left: 12px;"><?php echo 'Sugerido desde hace ' . timeSince($s->creado);?></label>
                                      <span class="pull-right">
                                            <span class="badge badge-primary badge-pill gray-background-color"> <?php echo $s->cantidad; ?> </span>
                                    </span>
                                  </a>
                              </td>
                              <td style="width: 1px;">
                              <span id="<?php echo $s->id; ?>" type="button" class="btn fa fa-trash termino_sugerido" aria-hidden="true" style="border:none;"></span></td>
                            </tr>
                        <?php
                        }
                        ?>
                </table>
            </div>
        </div>
        <div class="terminos-sugeridos-button-container">
            <button type="button" class="btn btn-primary terminos-sugeridos-button process-button" data-toggle="button" aria-pressed="false" autocomplete="off">
                Agregar un nuevo término
            </button>
        </div>
    </div>
</div>
<div class="body-container-admin hidden">
    <div class="body-maintenance-container">
        <div class="big-title">
            Agregar un nuevo término
        </div>
        <div class="body-maintenance-content">
            <form id="termino-add-form" action="" method="post" enctype="multipart/form-data">
                <div class="div-maintenance-termino">
                    <div class="body-maintenance-content-name">
                        <input id="termino-nombre" name="termino-nombre" class="form-control input-text input-term" type="text" style="float:left" placeholder="Término" required/><span></span>
                    </div>
                    <hr>
                    <div class="body-maintenance-content-origin padding-bottom-2">
                        <input id="termino-origen" name="termino-origen" class="form-control input-text input-term" type="text" placeholder="Origen"/>
                    </div>
                    <div class="body-maintenance-content-description padding-bottom-2">
                        <textarea id="termino-descripcion" name="termino-descripcion" class="form-control input-textarea height-90" cols="100" rows="10" placeholder="Escribe aquí el texto..."></textarea>
                    </div>
                    <div class="body-maintenance-content-images">
                        <label for="files">
                            <img src="img/file-upload.icon.svg" style="height: 90px; cursor: pointer"/>
                        </label>
                        <input type="file" id="files" name="files"/>
                    </div>
                </div>
                <div class="body-maintenance-content-button">
                    <input form="termino-add-form" class="btn btn-primary process-button" type="submit" value="Agregar el término"/>
                </div>
            </form>
        </div>
        <div class="body-maintenance-content-button-cancel">
            <a class="terminos-sugeridos-button-cancel" href="#">Cancelar</a>
        </div>
    </div>
    <div class="body-maintenance-edit-container">
        <div class="big-title">
            Modificar Término
        </div>
        <div class="body-maintenance-content">
            <form id="termino-edit-form" action="" method="post" enctype="multipart/form-data">
                <input id="termino-id" name="termino-id" type="hidden">
                <div class="div-maintenance-termino">
                    <div class="body-maintenance-content-name">
                        <input id="termino-nombre-edit" name="termino-nombre" class="form-control input-text" type="text" placeholder="Término" required/><span></span>
                    </div>
                    <hr>
                    <div class="body-maintenance-content-origin padding-bottom-2">
                        <input id="termino-origen-edit" name="termino-origen" class="form-control input-text" type="text" placeholder="Origen">
                    </div>
                    <div class="body-maintenance-content-description padding-bottom-2">
                        <textarea id="termino-descripcion-edit" name="termino-descripcion" class="form-control input-textarea" cols="100" rows="10" placeholder="Escribe aquí el texto..."></textarea>
                    </div>
                    <div class="body-maintenance-content-images">
                        <label for="files-edit">
                            <img src="img/file-upload.icon.svg" style="height: 90px; cursor: pointer"/>
                        </label>
                        <input type="file" id="files-edit" name="files"/>
                        <span id="span-preview" style="display: inline-block;position: relative;">
                            <img id="img-preview" class="img-preview" src="" style="height: 75px;">
                            <img id="remove-preview" class="remove" src="img/close-icon.png">
                        </span>
                        <input type="hidden" id="uploaded-image" name="uploaded-image" value="0">
                    </div>
                </div>
                <div class="body-maintenance-content-button">
                    <input form="termino-edit-form" type="submit" class="btn btn-primary process-button" value="Guardar cambios"/>
                </div>
            </form>
        </div>
        <div class="body-maintenance-content-button-cancel">
            <a class="terminos-sugeridos-button-cancel" href="#">Cancelar</a>
        </div>
    </div>
</div>