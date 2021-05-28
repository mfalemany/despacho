<?php 
class co_departamentos
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['departamento'])) {
			$where[] = "departamento ILIKE ".quote("%{$filtro['departamento']}%");
		}
		$sql = "SELECT
			t_d.id_departamento,
			t_d.departamento
		FROM
			departamentos as t_d
		ORDER BY departamento";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('despacho')->consultar($sql);
	}
}
?>