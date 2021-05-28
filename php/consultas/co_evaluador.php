<?php 
class co_evaluador{
	function get_evaluadores($id_proyecto)
	{
		$sql = "select doc.legajo, per.apellido||', '||per.nombres as docente
				from adscripciones.evaluador as eva
				left join adscripciones.docentes as doc on doc.legajo = eva.legajo
				left join adscripciones.personas as per on per.nro_documento = doc.nro_documento
				where eva.id_proyecto = $id_proyecto
				and eva.estado = 'A'
				and not exists (select * 
								from adscripciones.evaluacion_proyecto 
								where id_proyecto = eva.id_proyecto
								and legajo = doc.legajo 
								and resultado in ('R')
								)
				order by docente";
		$evaluadores = array();
		foreach(toba::db()->consultar($sql) as $evaluador){
			$evaluadores[$evaluador['legajo']] = $evaluador['docente'];
												
		}
		return $evaluadores;
	}
}
?>