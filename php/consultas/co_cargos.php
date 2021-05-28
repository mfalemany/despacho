<?php 
class co_cargos{
	function get_listado()
	{
		$sql = "SELECT
			t_c.cargo,
			t_c.id_cargo
		FROM
			cargos as t_c
		ORDER BY cargo";
		return toba::db('despacho')->consultar($sql);
	}
}
?>
