<?php
class co_resoluciones
{
	function get_listado($filtro=array())
	{

		$where = array();
		if (isset($filtro['nro_resol'])) {
			$where[] = "res.nro_resol = ".$filtro['nro_resol'];
		}
		if (isset($filtro['id_tipo_resolucion'])) {
			$where[] = "tipo.id_tipo_resolucion = ".quote($filtro['id_tipo_resolucion']);
		}
		if (isset($filtro['fecha_desde'])) {
			$where[] = "res.fecha >= ".quote($filtro['fecha_desde']);
		}
		if (isset($filtro['fecha_hasta'])) {
			$where[] = "res.fecha <= ".quote($filtro['fecha_hasta']);
		}
		//filtro de palabras clave
		if (isset($filtro['palabra_clave'])) {
			//si el filtro no viene vacio
			if(strlen(trim($filtro['palabra_clave']))){
				//verifico si se ingres?solo una palabra, o varias separadas por coma
				if(strpos($filtro['palabra_clave'],',')){
					//en caso de ser muchas, las agrego al where una por una
					foreach(explode(',',$filtro['palabra_clave']) as $palabra)
					{
						$where[] = "lower(res.palabras_clave) LIKE ".quote("%".trim($palabra)."%");			
					}
				}else{
					//si solo se ingres?una, la agrego individualmente al where
					$where[] = "lower(res.palabras_clave) LIKE ".quote("%".trim($filtro['palabra_clave'])."%");		
				}	
			}
			
		}
		$sql = "SELECT res.nro_resol, res.id_tipo_resolucion, tipo.tipo_resolucion, res.fecha, res.palabras_clave
				FROM resoluciones as res
				LEFT JOIN tipos_resolucion as tipo on res.id_tipo_resolucion = tipo.id_tipo_resolucion
				ORDER BY res.nro_resol";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//echo $sql; 
		
		return toba::db('despacho')->consultar($sql);
	}

	function get_nombre_pdf($id_tipo_resolucion,$nro_resol=NULL)
	{	
		if( ! ($id_tipo_resolucion && $nro_resol)){
			return false;
		}
		
		$sql = "SELECT archivo_pdf FROM resoluciones WHERE nro_resol = $nro_resol and id_tipo_resolucion = $id_tipo_resolucion";
		$resultado = toba::db()->consultar($sql);
		if(count($resultado)){
			return ($resultado[0]['archivo_pdf']) ? $resultado[0]['archivo_pdf']: false;
		}else{
			return false;
		}
	}

	function existe($nro_resol,$id_tipo_resolucion){
		$sql = "SELECT * FROM resoluciones WHERE nro_resol = ".quote($nro_resol)." AND id_tipo_resolucion = ".quote($id_tipo_resolucion);
		$resultado = toba::db('despacho')->consultar($sql);
		return count($resultado)? TRUE: FALSE;
	}

	function get_palabras_clave($nro_resol = NULL){
		//variable de retorno
		$palabras_clave = array();
		
		if( ! $nro_resol){
			return $palabras_clave;
		}

		$sql = "SELECT palabras_clave FROM resoluciones WHERE nro_resol = ".$nro_resol;
		
		//transformo el string en un array de palabras clave
		$resultado = toba::db('despacho')->consultar_fila($sql);
		
		if(isset($resultado['palabras_clave'])){
			if(strlen(trim($resultado['palabras_clave'])) > 0){
				$palabras = explode("/",$resultado['palabras_clave']);		
				foreach($palabras as $palabra)
				{
					$palabras_clave[] = array('palabra_clave' => $palabra);

				}
			}
		}
		return $palabras_clave;
	}

	static function get_descripcion($params)
	{
		if( ! is_array($params)){
			$params = explode('||',$params);
		}
		$id_tipo_resolucion = $params[0];
		$nro_resol = $params[1];	
		
		$sql = "SELECT res.nro_resol, tipo.tipo_resolucion, res.fecha, substring(extract(year from res.fecha)::character(4),3,2) as anio, res.palabras_clave
				FROM resoluciones as res
				LEFT JOIN tipos_resolucion as tipo on res.id_tipo_resolucion = tipo.id_tipo_resolucion
				WHERE res.nro_resol = ".quote($nro_resol);
				if(isset($id_tipo_resolucion)){
					$sql .= " AND res.id_tipo_resolucion = ".quote($id_tipo_resolucion);
				}
				$sql .= " ORDER BY res.nro_resol";
		$res = toba::db()->consultar($sql);
		return (count($res)) ? "Res. ".$res[0]['nro_resol']."/".$res[0]['anio']." (".$res[0]['tipo_resolucion'].")" : "No encontrada";	
	}
}

?>