<?php
class co_docentes_cargos
{
	function get_listado($docente)
	{
		$sql = "SELECT dc.legajo, dc.id_cargo, car.cargo, dc.id_dedicacion, ded.dedicacion
				FROM docentes_cargos as dc
				LEFT JOIN cargos as car on car.id_cargo = dc.id_cargo
				LEFT JOIN dedicaciones as ded on ded.id_dedicacion = dc.id_dedicacion
				WHERE dc.legajo = '".$docente['legajo']."'";
		return toba::db('despacho')->consultar($sql);
	}

	function agregar_cargo($id_cargo, $id_dedicacion, $legajo_docente){
		$sql = "INSERT INTO docentes_cargos VALUES (".quote($legajo_docente).",$id_cargo,$id_dedicacion)";
		return toba::db('adscripciones')->ejecutar($sql);

	}
}

?>