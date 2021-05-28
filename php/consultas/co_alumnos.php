<?php
class co_alumnos
{
	function get_listado($filtro=array(),$reducido=false)
	{
		$where = array();
		if (isset($filtro['carrera'])) {
			$where[] = "alu.carrera = ".quote("{$filtro['carrera']}");
		}
		if (isset($filtro['apellido'])) {
			$where[] = "per.apellido ILIKE ".quote("%{$filtro['apellido']}%");
		}
		if (isset($filtro['nombres'])) {
			$where[] = "per.nombres ILIKE ".quote("%{$filtro['nombres']}%");
		}
		if (isset($filtro['nro_documento'])) {
			//busca si la persona existe en local, si no existe la busca en WS
			toba::consulta_php('co_personas')->existe_persona($filtro['nro_documento']);
			$where[] = "per.nro_documento = ".quote($filtro['nro_documento']);
		}
		if (isset($filtro['legajo'])) {
			$where[] = "alu.legajo = ".quote($filtro['legajo']);
		}
		if($reducido){
			$ayn = "per.apellido||', '||per.nombres as alumno";
		}else{
			$ayn = "per.apellido, per.nombres";
		}
		$sql = "SELECT alu.legajo, per.nro_documento, alu.nro_inscripcion, alu.anio_ingreso, $ayn, per.e_mail, per.fecha_nac, per.telefono
				FROM alumnos as alu
				LEFT JOIN personas as per on per.nro_documento = alu.nro_documento

		ORDER BY per.apellido";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db()->consultar($sql);
	}

	static function get_ayn($legajo)
	{
		$sql = "SELECT apellido||', '||nombres as ayn 
				FROM personas as per
				LEFT JOIN alumnos as alu on alu.nro_documento = per.nro_documento 
				WHERE alu.legajo = ".quote($legajo);
		$resultado = toba::db('despacho')->consultar_fila($sql);
		if(isset($resultado['ayn'])){
			return $resultado['ayn'];	
		}else{
			return NULL;
		}
	}

}

?>