<?php
class co_modifica_resolucion
{
		/**
	 * Parametros
	 * @param $original (number) Nro de Resolucion Original
	 * 
	 * @return array con las resoluciones a las que "original" modifica
	*/
	function get_modifica_a($original)
	{
		$sql = "SELECT mr.nro_resol_modifica, tr.tipo_resolucion 
				FROM modifica_resolucion as mr
				LEFT JOIN resoluciones as re on re.nro_resol = mr.nro_resol_original
				LEFT JOIN tipos_resolucion as tr on re.id_tipo_resolucion = tr.id_tipo_resolucion
				WHERE mr.nro_resol_original = ".intval($original);
		return toba::db()->consultar($sql);
	}

	function get_resol_modificadoras($nro_resol,$id_tipo_resolucion)
	{
		$modificadoras = array();
		$sql = "SELECT nro_resol_original, tr.tipo_resolucion, date_part('year',re.fecha) as fecha
				FROM modifica_resolucion as mr
				LEFT JOIN resoluciones as re on re.nro_resol = mr.nro_resol_original
				LEFT JOIN tipos_resolucion as tr on re.id_tipo_resolucion = tr.id_tipo_resolucion
				WHERE mr.nro_resol_modifica = ".quote(intval($nro_resol))." AND mr.id_tipo_resol_modif = ".quote($id_tipo_resolucion);

		$resultado = toba::db()->consultar($sql);
		foreach($resultado as $clave => $valor){
			$modificadoras[]['nro_resol_original'] = "Res. ".$valor['nro_resol_original']."/".$valor['fecha']." (".$valor['tipo_resolucion'].")";
		}
		return $modificadoras;

	}
}

?>