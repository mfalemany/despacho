<?php 
class co_modalidad{
	function get_listado()
	{
		$sql = "SELECT
			t_m.id_modalidad,
			t_m.modalidad,
			t_m.activo
		FROM
			modalidad as t_m
		ORDER BY modalidad";
		return toba::db('despacho')->consultar($sql);
	}
}
?>