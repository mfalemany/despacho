<?php

class co_adscripciones
{
	function get_resoluciones_adscripcion($filtro=array())
	{
		$where = array();
		if (isset($filtro['nro_resol'])) {
			$where[] = "res.nro_resol = ".$filtro['nro_resol'];
		}
		$sql = "SELECT count(*) as cant_ads,
						res.nro_resol, 
						res.id_tipo_resolucion,
						res.fecha, 
						tr.tipo_resolucion_corto
				FROM adscripciones AS ads 
				LEFT JOIN resoluciones as res ON ads.nro_resol = res.nro_resol
				LEFT JOIN tipos_resolucion as tr on tr.id_tipo_resolucion = res.id_tipo_resolucion
				GROUP BY res.nro_resol, res.fecha, tr.tipo_resolucion_corto, res.id_tipo_resolucion
				ORDER BY res.fecha DESC";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('despacho')->consultar($sql);
	}

	function get_adscripciones($nro_resol){
		$sql = "SELECT * FROM adscripciones WHERE nro_resol = '$nro_resol'";
		return toba::db('despacho')->consultar($sql);
	}


	/**
	 * [get_adscripciones_descripciones Retorna un listado de las adscripciones incluidas en una determinada resolución. El resultado se usa para llenar un cuadro, por ejemplo, ya que retorna todas las columnas de una adscripcion, con sus respectivas descripciones]
	 * @param  integer $nro_resol Número de la resolución cuyas adscripciones se quieren obtener
	 * @return array()            Retorna las adscripciones contenidas en la resolución recibida como parámetro
	 */
	function get_adscripciones_descripciones($nro_resol)
	{
		$sql = "SELECT per.nro_documento,
						per.apellido||', '||per.nombres AS ayn_adscripto,
						perdoc.apellido||', '||perdoc.nombres AS ayn_responsable,
						ads.fecha_inicio,
						ads.fecha_fin,
					   ads.id_catedra,
					   cat.catedra
				FROM adscripciones AS ads
				LEFT JOIN personas AS per ON per.nro_documento = ads.nro_documento
				LEFT JOIN docentes AS doc ON ads.responsable = doc.legajo
				LEFT JOIN personas AS perdoc ON perdoc.nro_documento = doc.nro_documento
				LEFT JOIN catedras as cat ON cat.id_catedra = ads.id_catedra
				WHERE ads.nro_resol = ".quote($nro_resol);
		return toba::db()->consultar($sql);		
	}


	function get_reporte($filtro = array())
	{
		$where = array();
		
		if (isset($filtro['nro_documento'])) {
			$where[] = "ads.nro_documento = ".quote($filtro['nro_documento']);
		}
		if (isset($filtro['responsable'])) {
			$where[] = "doc.legajo = ".quote($filtro['responsable']);
		}
		if (isset($filtro['tipo_adscripcion'])) {
			$where[] = "ads.id_tipo_adscripcion = ".$filtro['tipo_adscripcion'];
		}
		if (isset($filtro['calidad'])) {
			$where[] = "alu.calidad = ".quote($filtro['calidad']);
		}
		if (isset($filtro['estado'])) {
			switch ($filtro['estado']) {
				case 'Vigente':
					$where[] = "ads.fecha_fin >= date(".quote(date('Y-m-d')).")";
					break;
				case 'No vigente':
					$where[] = "ads.fecha_fin < date(".quote(date('Y-m-d')).")";
					break;
			}	
		}
		if (isset($filtro['catedra'])) {
			$where[] = "ads.id_catedra = ".$filtro['catedra'];
		}
		
		$sql = "SELECT ads.nro_resol, 
						res.id_tipo_resolucion, 
						tr.tipo_resolucion,
						res.fecha, 
						ads.fecha_inicio, 
						ads.fecha_fin, 
						ads.id_tipo_adscripcion, 
						ta.tipo_adscripcion,
						ads.responsable,
						perdoc.apellido||', '||perdoc.nombres as docente,
						ads.nro_documento, 
						peralu.apellido||', '||peralu.nombres as alumno,
						ads.id_catedra,
						cat.catedra
				FROM adscripciones as ads
				LEFT JOIN alumnos as alu on alu.nro_documento = ads.nro_documento
				LEFT JOIN resoluciones as res on res.nro_resol = ads.nro_resol
				LEFT JOIN tipos_resolucion as tr on tr.id_tipo_resolucion = res.id_tipo_resolucion
				LEFT JOIN tipos_adscripcion as ta on ta.id_tipo = ads.id_tipo_adscripcion
				LEFT JOIN docentes as doc on doc.legajo = ads.responsable
				LEFT JOIN personas as perdoc on perdoc.nro_documento = doc.nro_documento
				LEFT JOIN personas as peralu on peralu.nro_documento = ads.nro_documento
				LEFT JOIN catedras as cat on cat.id_catedra = ads.id_catedra";
				
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//var_dump(toba::db('despacho')->consultar($sql));
		return toba::db('despacho')->consultar($sql);
	}
}

?>