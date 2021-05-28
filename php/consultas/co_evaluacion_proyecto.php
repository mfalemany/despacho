<?php 
class co_evaluacion_proyecto{
	function get_evaluaciones($id_proyecto)
	{
		$sql = "select eva.id_proyecto, 
						doc.legajo, 
						per.apellido||', '||per.nombres as docente, 
						eva.resultado as resultado,
						case eva.resultado 
							when 'M' then 'Solicita modificaciones' 
							when 'A' then 'Aceptado' 
							when 'C' then 'Acepta modificaciones' 
							when 'R' then 'Rechazado' end as evaluacion, 
						eva.fecha, 
						eva.observaciones
				from adscripciones.evaluacion_proyecto as eva 
				left join adscripciones.docentes as doc on doc.legajo = eva.legajo 
				left join adscripciones.personas as per on per.nro_documento = doc.nro_documento 
				where eva.id_proyecto = $id_proyecto
				ORDER BY fecha DESC";
				//echo nl2br($sql);
		return toba::db()->consultar($sql);
	}

	function get_posibles_resultados_por_evaluador($id_proyecto,$legajo)
	{
		$sql = "select resultado
				from adscripciones.evaluacion_proyecto as ep
				where ep.legajo = ".quote($legajo)."
				and ep.id_proyecto = ".quote($id_proyecto)."
				and id_evaluacion = (
					select max(id_evaluacion)
					from adscripciones.evaluacion_proyecto
					where legajo = ep.legajo
					and id_proyecto = ep.id_proyecto
				)";
		$resp = toba::db()->consultar_fila($sql);		
		return $resp['resultado'] ? $resp['resultado'] : NULL;

	}
}
?>