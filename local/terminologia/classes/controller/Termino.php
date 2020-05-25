<?php

namespace LocalPages\Controller;

require_once realpath(dirname(__FILE__)) . '/../model/Termino.php';


use LocalPages\Termino as TerminoModel;

class Termino {
	public function __construct() {
	}

	private function getTerminoModel() {
		return new TerminoModel();
	}

	/**
	 * Get Termino element by letter
	 *
	 * @param string $letra
	 * @return array
	 */
	public function ObtenerTerminoPorLetra($letra) {
		return $this->getTerminoModel()->ObtenerTerminoPorLetra($letra);
	}

	/**
	 * Get Termino element by ID
	 *
	 * @param int $id
	 * @return array
	 */
	public function ObtenerTerminoPorId($id) {
		return $this->getTerminoModel()->ObtenerTerminoPorId(intval($id));
	}

	/**<
	 * Agregar Termino
	 *
	 * @param array $params
	 * @param array $filesArr
	 * @return bool
	 */
	public function AgregarTermino($params, $filesArr) {
		return $this->getTerminoModel()->AgregarTermino($params, $filesArr);
	}

	/**
	 * Editar Termino
	 *
	 * @param array $params
	 * @param array $filesArr
	 * @return bool
	 */
	public function EditarTermino($params, $filesArr) {
		return $this->getTerminoModel()->EditarTermino($params, $filesArr);
	}

	/**
	 * Eliminar Termino
	 *
	 * @param integer $id
	 * @return bool
	 */
	public function EliminarTermino($id) {
		return $this->getTerminoModel()->EliminarTermino($id);
	}


	/**
	 * Agregar Sugerencia
	 *
	 * @param string $terminoSugerido
	 * @return bool
	 */
	public function AgregarSugerencia($terminoSugerido) {
		return $this->getTerminoModel()->AgregarSugerencia($terminoSugerido);
	}

	/**
	 * Eliminar Sugerencia
	 *
	 * @param integer $id
	 * @return bool
	 */
	public function EliminarSugerencia($id) {
		return $this->getTerminoModel()->EliminarSugerencia($id);
	}

	/**
	 * Agregar Termino
	 *
	 * @param array $params
	 * @return array
	 */
	public function GetSugerencias() {
		return $this->getTerminoModel()->GetSugerencias();
	}

	/**
	 * Get terminos mas buscados
	 *
	 * @return array
	 */
	public function GetMasBuscados() {
		return $this->getTerminoModel()->GetMasBuscados();
	}

	/**
	 * Get terminos busqueda
	 *
	 * @return array
	 */
	public function GetTerminoBusqueda($keyword) {
		return $this->getTerminoModel()->GetTerminoBusqueda($keyword);
	}

	/**
	 * Add termino visita
	 *
	 * @return array
	 */
	public function AgregarTerminoVisita($id) {
		return $this->getTerminoModel()->AgregarTerminoVisita($id);
	}


}