<?php 
class co_dedicaciones{
	function get_listado()
	{
		$sql = "SELECT
			t_d.id_dedicacion,
			t_d.dedicacion
		FROM
			dedicaciones as t_d
		ORDER BY dedicacion";
		return toba::db('despacho')->consultar($sql);
	}
}
?>