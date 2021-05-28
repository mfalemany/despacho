<?php 
class co_estado_proyecto{
	function get_listado()
	{
		$sql = "SELECT
			t_ep.id_estado,
			t_ep.estado_proyecto
		FROM
			estado_proyecto as t_ep
		ORDER BY estado_proyecto";
		return toba::db('despacho')->consultar($sql);
	}
}
?>