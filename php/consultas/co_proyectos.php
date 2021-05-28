<?php 
class co_proyectos{
	function obtener($filtro = array())
	{
		$where = array();
		if(count($filtro)){
			if(isset($filtro['alumno'])){
				$where[] = "peralu.apellido||','||peralu.nombres ILIKE ".quote("%".$filtro['alumno']."%");
			}
			if(isset($filtro['asesor'])){
				$where[] = "per.apellido||','||per.nombres ILIKE ".quote("%".$filtro['asesor']."%");
			}
			if(isset($filtro['evaluador'])){
				$where[] = "proy.id_proyecto in (select id_proyecto
							from adscripciones.evaluador as eva
							left join adscripciones.docentes as doc on doc.legajo = eva.legajo
							left join adscripciones.personas as per on per.nro_documento = doc.nro_documento
							where per.apellido||', '||per.nombres ilike ".quote("%".$filtro['evaluador']."%").")";
			}
			
			
			if(isset($filtro['nro_resol'])){
				//Si es un array, es porque viene de una búsqueda de popup que retorna nro y tipo de resol
				if(is_array($filtro['nro_resol'])){
					$where[] = "proy.id_tipo_resolucion = ".$filtro['nro_resol'][1];
					$filtro['nro_resol'] = $filtro['nro_resol'][0];
				}
				$where[] = "proy.nro_resol = ".quote($filtro['nro_resol']);
			}
			if(isset($filtro['desde'])){
				$where[] = "resol.fecha >= ".quote($filtro['desde']);
			}
			if(isset($filtro['hasta'])){
				$where[] = "resol.fecha <= ".quote($filtro['hasta']);
			}
			if(isset($filtro['id_modalidad'])){
				$where[] = "mod.id_modalidad = ".$filtro['id_modalidad'];
			}
			if(isset($filtro['estado'])){
				$where[] = "proy.estado = ".quote($filtro['estado']);
			}
			
			if(isset($filtro['incluye_egresados'])){
				if($filtro['incluye_egresados'] == 0){
					$where[] = "proy.estado <> (select id_estado from adscripciones.estado_proyecto where LOWER(estado_proyecto) like '%egresad%')";
				}
				
			}
		}

		$sql = "select proy.id_proyecto, 
					alu.legajo, 
					peralu.apellido||', '||peralu.nombres as alumno, 
					per.apellido||', '||per.nombres as asesor,
					proy.tema,
					mod.modalidad,
					ep.estado_proyecto,
					resol.nro_resol
				from adscripciones.proyecto as proy
				left join adscripciones.alumnos as alu on alu.legajo = proy.alu_legajo
				left join adscripciones.personas as per on per.nro_documento = proy.asesor_dni
				left join adscripciones.personas as peralu on peralu.nro_documento = alu.nro_documento
				left join adscripciones.modalidad as mod on mod.id_modalidad = proy.id_modalidad
				left join adscripciones.resoluciones as resol on resol.nro_resol = proy.nro_resol 
				left join adscripciones.estado_proyecto as ep on ep.id_estado = proy.estado
			order by alumno";

		
			
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		//echo nl2br($sql);
		return toba::db()->consultar($sql);        
	}

	//retorna TRUE en caso que el nro_documento no se encuentre en otro registro como asesor_dni
	function es_asesor_nuevo($nro_documento)
	{
		$sql = "select count(*) as cantidad from proyecto where asesor_dni = ".quote($nro_documento);
		return toba::db()->consultar_fila($sql)['cantidad'] ? FALSE: TRUE;

	}

	/**
	 * Retorna un listado de modalidad-cantidad (si se pasa un año, solo considera ese)
	 * @return array
	 */
	function get_resumen($anio = 0)
	{
		$sql = 'select count(*) as cantidad, mod.modalidad 
			from adscripciones.proyecto as proy
			left join adscripciones.modalidad as mod on mod.id_modalidad = proy.id_modalidad
			left join adscripciones.resoluciones as res on res.nro_resol = proy.nro_resol';
		if ($anio) {
			$sql .= ' where extract(YEAR from res.fecha) = '.$anio;
		}
		$sql .= ' GROUP BY mod.modalidad';
		return toba::db()->consultar($sql);    
	}

	function actualizar_estado_proyecto($id_proyecto)
	{
		if( ! $id_proyecto){
			return;
		}
		$consulta = 'select ep.resultado 
					from adscripciones.evaluacion_proyecto as ep
					where ep.id_proyecto = '.$id_proyecto.'
					and id_evaluacion = (select max(id_evaluacion) from adscripciones.evaluacion_proyecto where id_proyecto = ep.id_proyecto and legajo = ep.legajo)';
		$evaluaciones = toba::db()->consultar($consulta);
		
		$resultados = array();
		if(count($evaluaciones)){
			foreach($evaluaciones as $evaluacion){
				$resultados[] = $evaluacion['resultado'];
			}
		}else{
			//Presentado (Estado Inicial)
			$sql = 'UPDATE adscripciones.proyecto SET estado = \'P\' where id_proyecto = '.$id_proyecto;
		}
		//Si al menos un jurado rechazó el proyecto, se considera rechazado
		if(in_array('R',$resultados)){
			$sql = 'UPDATE adscripciones.proyecto SET estado = \'R\' where id_proyecto = '.$id_proyecto;
		//Si nadie rechazó, pero al menos uno solicitó modificaciones, se considera como tal
		}elseif(in_array('M',$resultados)){
			$sql = 'UPDATE adscripciones.proyecto SET estado = \'M\' where id_proyecto = '.$id_proyecto;
		//si nadie rechazó, y tampoco solicitó modificaciones, se verifica si TODOS aceptaron para considerar el proyecto como aceptado
		}else{
			$cantidades = array_count_values($resultados);
			if(array_key_exists('A',$cantidades)){
				//si no hay rechazo, ni pedido de modificaciones
				//puede estar aceptado (si todos aceptaron)
				if($cantidades['A'] == 3){
					$sql = 'UPDATE adscripciones.proyecto SET estado = \'A\' where id_proyecto = '.$id_proyecto;
				}else{
					//o en espera de evaluacion (si uno o dos aceptaron, pero no los tres)
					$sql = 'UPDATE adscripciones.proyecto SET estado = \'W\' where id_proyecto = '.$id_proyecto;
				}
			}
		}
		//si ocurrieron alguno de los tres escenarios anteriores, se ejecuta la consulta
		if(isset($sql)){
			toba::db()->ejecutar($sql);	
		}
		

	}

	function get_proyectos_por_evaluador($legajo = NULL)
	{
		$sql = "select 
				peralu.apellido||', '||peralu.nombres as alumno, 
				per.apellido||', '||per.nombres as asesor,
				proy.tema,
				mod.modalidad,
				est.estado_proyecto as estado
				from adscripciones.proyecto as proy
				left join adscripciones.estado_proyecto as est on est.id_estado = proy.estado
				left join adscripciones.alumnos as alu on alu.legajo = proy.alu_legajo
				left join adscripciones.personas as per on per.nro_documento = proy.asesor_dni
				left join adscripciones.personas as peralu on peralu.nro_documento = alu.nro_documento
				left join adscripciones.modalidad as mod on mod.id_modalidad = proy.id_modalidad
				left join adscripciones.evaluador as eva on eva.id_proyecto = proy.id_proyecto
				left join adscripciones.docentes as doc on doc.legajo = eva.legajo
				where est.id_estado in ('M','P','W')";
		if($legajo){
			$sql .= " AND doc.legajo = ".quote($legajo);	
		}		
		$sql .= " order by alumno";
		return toba::db()->consultar($sql);        
	}



}
?>