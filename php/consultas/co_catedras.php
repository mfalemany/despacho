<?php
class co_catedras
{
	function get_listado($id_catedra = null){
		$sql = "SELECT cat.id_catedra, cat.catedra, dep.id_departamento, dep.departamento
				FROM catedras as cat
				LEFT JOIN departamentos as dep on dep.id_departamento = cat.id_departamento";
		if($id_catedra){
			$sql .= " WHERE id_catedra = $id_catedra";
		}
		$sql .= " ORDER BY cat.catedra";
		return toba::db('despacho')->consultar($sql);
	}

	function get_departamento($id_catedra){
		$sql = "SELECT dep.id_departamento
				FROM catedras as cat
				LEFT JOIN departamentos as dep on dep.id_departamento = cat.id_departamento
				WHERE id_catedra = $id_catedra";
		return toba::db('despacho')->consultar($sql);
	}
	function get_descripciones()
	{
		$sql = "SELECT id_catedra, catedra FROM catedras ORDER BY catedra";
		return toba::db('despacho')->consultar($sql);
	}

	function get_descripciones_combo_editable($patron)
	{
		$sql = "SELECT id_catedra, catedra 
				FROM catedras 
				WHERE catedra ILIKE '%$patron%'
				ORDER BY catedra";
		return toba::db('despacho')->consultar($sql);
	}

	function get_catedra($id_catedra)
	{
		$sql = "SELECT catedra FROM catedras WHERE id_catedra = $id_catedra";
		$resultado = toba::db('despacho')->consultar($sql);
		return $resultado[0]['catedra'];
	}
}

?>