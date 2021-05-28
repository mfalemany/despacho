<?php
class co_docentes
{
	function get_listado($filtro = array())
	{

		$where = array();

		$sql = "SELECT per.nro_documento, 
						doc.legajo, 
						per.apellido, 
						per.nombres, 
						per.apellido||', '||per.nombres AS ayn, 
						per.e_mail, 
						per.fecha_nac, 
						per.telefono
				FROM docentes as doc
				LEFT JOIN personas as per on per.nro_documento = doc.nro_documento";
		//var_dump($filtro); return;
		if (isset($filtro['nro_documento'])) {
			$where[] = "per.nro_documento ILIKE ".quote("%{$filtro['nro_documento']}%");
		}
		if (isset($filtro['apellido'])) {
			$where[] = "per.apellido ILIKE ".quote("%{$filtro['apellido']}%");
		}
		if (isset($filtro['nombres'])) {
			$where[] = "per.nombres ILIKE ".quote("%{$filtro['nombres']}%")." OR per.nombres ILIKE ".quote("%{$filtro['nombres']}%");
		}
		if (isset($filtro['legajo'])) {
			$where[] = "doc.legajo ILIKE ".quote("%{$filtro['legajo']}%");
		}
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//var_dump($sql);

		
		return toba::db('despacho')->consultar($sql);

	}

	function get_listado_abreviado($filtro = array())
	{
		
		$sql = "SELECT per.nro_documento, doc.legajo, per.apellido||', '||per.nombres as docente
				FROM docentes as doc
				LEFT JOIN personas as per on per.nro_documento = doc.nro_documento
				ORDER BY docente";
		
		$where = array();
		
		if (isset($filtro['nro_documento'])) {
			$where[] = "per.nro_documento ILIKE ".quote("%{$filtro['nro_documento']}%");
		}
		if (isset($filtro['docente'])) {
			$where[] = "per.apellido ILIKE ".quote("%{$filtro['docente']}%")." OR per.nombres ILIKE ".quote("%{$filtro['docente']}%");
		}
		if (isset($filtro['legajo'])) {
			$where[] = "doc.legajo ILIKE ".quote("%{$filtro['legajo']}%");
		}
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('despacho')->consultar($sql);


	}

	function get_personas_no_docentes($filtro = array()){
		$sql = "SELECT per.nro_documento, per.apellido, per.nombres, per.e_mail, per.fecha_nac, per.telefono
				FROM personas as per";

		$where = array();
		
		//solo me interesan las personas que no estén registradas como docentes
		$where[] = "per.nro_documento NOT IN (select nro_documento from docentes)";

		//demas filtros
		if (isset($filtro['nro_documento'])) {
			$where[] = "nro_documento ILIKE ".quote("%{$filtro['nro_documento']}%");
		}
		if (isset($filtro['apellido'])) {
			$where[] = "apellido ILIKE ".quote("%{$filtro['apellido']}%");
		}
		if (isset($filtro['nombres'])) {
			$where[] = "nombres ILIKE ".quote("%{$filtro['nombres']}%");
		}

		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		
		return toba::db('despacho')->consultar($sql);		
	}

	static function get_ayn($legajo)
	{
		if(!$legajo){
			return '';
		}
		$sql = "SELECT apellido||', '||nombres as ayn FROM personas WHERE nro_documento = (
					SELECT nro_documento FROM docentes WHERE legajo = '$legajo')";
		$resultado = toba::db('despacho')->consultar($sql);
		return $resultado[0]['ayn'];
	}

	function get_cargos($legajo_docente)
	{
		$sql = "SELECT * FROM docentes WHERE legajo = ".quote($legajo_docente);
		return toba::db('despacho')->consultar($sql);
	}

	function get_docentes_ef_editable($patron)
	{
		$sql = "SELECT doc.legajo, per.apellido||', '||per.nombres as nombre
				FROM docentes AS doc
				LEFT JOIN personas AS per ON per.nro_documento = doc.nro_documento
				WHERE per.apellido||' '||per.nombres ilike ".quote("%".$patron."%");
		return toba::db()->consultar($sql);		
	}


}

?>