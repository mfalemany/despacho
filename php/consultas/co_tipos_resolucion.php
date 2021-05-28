<?php
class co_tipos_resolucion
{
	function get_listado()
	{
		$sql = "SELECT
			t_tr.id_tipo_resolucion,
			t_tr.tipo_resolucion,
			t_tr.tipo_resolucion_corto
		FROM
			tipos_resolucion as t_tr
		ORDER BY tipo_resolucion";
		return toba::db('despacho')->consultar($sql);
	}

	function get_descripciones()
	{
		$sql = "SELECT id_tipo_resolucion, tipo_resolucion FROM tipos_resolucion ORDER BY tipo_resolucion";
		return toba::db('despacho')->consultar($sql);
	}

}

?>