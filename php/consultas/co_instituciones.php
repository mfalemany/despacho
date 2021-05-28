<?php
class co_instituciones
{
	function get_instituciones($filtro = array())
	{
		$where = array();
		if(isset($filtro['institucion'])){
			$where[] = 'inst.institucion ILIKE ' . quote('%'.$filtro['institucion'].'%');
		}
		$sql = "SELECT inst.id_institucion, inst.institucion FROM instituciones AS inst ORDER BY inst.institucion";
		if(count($where)){
			$sql = sql_concatenar_where($sql,$where);
		}
		return toba::db()->consultar($sql);
	}

	static function get_nombre_institucion($id_institucion)
	{
		$sql = "SELECT institucion FROM instituciones WHERE id_institucion = ".quote($id_institucion);
		$resultado = toba::db()->consultar_fila($sql);
		return (count($resultado)) ? $resultado['institucion'] : 'Institución no encontrada';

	}

}

?>