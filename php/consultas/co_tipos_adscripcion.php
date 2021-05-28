<?php
class co_tipos_adscripcion
{
	function get_listado()
	{
		$sql = "SELECT
			t_ta.id_tipo,
			t_ta.tipo_adscripcion
		FROM
			tipos_adscripcion as t_ta
		ORDER BY tipo_adscripcion";
		return toba::db('despacho')->consultar($sql);
	}

	function get_descripciones()
	{
		$sql = "SELECT id_tipo, tipo_adscripcion FROM tipos_adscripcion ORDER BY tipo_adscripcion";
		return toba::db('despacho')->consultar($sql);
	}

	function get_tipo_adscripcion($id_tipo){
		$sql = "SELECT tipo_adscripcion FROM tipos_adscripcion WHERE id_tipo = $id_tipo";
		$resultado = toba::db('despacho')->consultar($sql);
		return $resultado[0]['tipo_adscripcion'];
	}
}
?>