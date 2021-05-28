<?php
class co_cartas_acuerdo
{
	function get_instituciones()
	{
		$sql = "SELECT id_institucion, institucion 
		FROM instituciones 
		ORDER BY institucion";
		return toba::db('despacho')->consultar($sql);
	}

	function get_cartas_acuerdo($filtro = array())
	{
		$where = array();
		if(isset($filtro['nro_resol'])){
			$where[] = 'resol.nro_resol = '.quote($filtro['nro_resol']);
		}
		if(isset($filtro['parte'])){
			$where[] = 'car.id_carta_acuerdo IN (SELECT id_carta_acuerdo FROM partes_acuerdo WHERE id_institucion in (SELECT id_institucion FROM instituciones WHERE institucion ILIKE '.quote('%'.$filtro['parte'].'%')."))";
		}
		if(isset($filtro['vigencia'])){
			if($filtro['vigencia'] == 'N'){
				$where[] = 'car.fecha_hasta < current_date';
			}
			if($filtro['vigencia'] == 'V'){
				$where[] = 'car.fecha_hasta >= current_date';
			}
		}
		if(isset($filtro['responsable'])){
			$where[] = "car.nro_documento_responsable in (SELECT nro_documento FROM personas WHERE apellido||nombres ILIKE ".quote('%'.$filtro['responsable'].'%').")";
		}
		$sql = "SELECT car.id_carta_acuerdo,
						car.nro_resol,
						resol.id_tipo_resolucion,
						resol.fecha,
						car.nro_documento_responsable,
						per.apellido||', '||per.nombres as responsable,
						car.objetivo,
						car.fecha_desde,
						car.fecha_hasta,
						car.observaciones,
						car.esta_firmado,
						array_to_string(array_agg(inst.institucion), ' / ') AS partes
				FROM adscripciones.cartas_acuerdo AS car
				LEFT JOIN adscripciones.partes_acuerdo as partes ON partes.id_carta_acuerdo = car.id_carta_acuerdo
				LEFT JOIN adscripciones.instituciones AS inst ON inst.id_institucion = partes.id_institucion
				LEFT JOIN adscripciones.personas AS per ON per.nro_documento = car.nro_documento_responsable
				LEFT JOIN adscripciones.resoluciones AS resol ON resol.nro_resol = car.nro_resol
				GROUP BY 
					car.id_carta_acuerdo,
					resol.id_tipo_resolucion,
					resol.fecha,
					car.nro_documento_responsable,
					per.apellido||', '||per.nombres,
					car.objetivo,
					car.fecha_desde,
					car.fecha_hasta,
					car.observaciones,
					car.esta_firmado";
		if(count($where)){
			$sql = sql_concatenar_where($sql,$where);
		}
		return toba::db()->consultar($sql);
	}

}
?>