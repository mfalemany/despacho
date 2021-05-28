<?php
class co_guarani
{
	function buscar_personas($filtro = array())
	{
		$conexion = new dt_guarani();
		if( ! $conexion){
			toba::notificacion()->agregar('No se pudo conectar a la Base de Datos de SIU-Guaran&iacute;. Por favor, revise los par&aacute;metros de conexion en el archivo guarani.json');
			return;
		}
		
		$sql = "select first 1 per.apellido, 
				        per.nombres,
				        alu.legajo,
				        alu.carrera,
				        per.nro_documento, 
				        per.nro_inscripcion,
				        per.fecha_nacimiento,
				        (select celular_numero 
				        from sga_datos_cen_aux as cen
				        where cen.celular_numero is not null
				        and cen.fecha_relevamiento = (select max(fecha_relevamiento) from sga_datos_cen_aux where nro_inscripcion = cen.nro_inscripcion and celular_numero is not null)
				        and cen.nro_inscripcion = per.nro_inscripcion) as celular_numero,
				        (select e_mail
				        from sga_datos_censales as cen
				        where cen.e_mail is not null
				        and cen.fecha_relevamiento = (select max(fecha_relevamiento) from sga_datos_cen_aux where nro_inscripcion = cen.nro_inscripcion and e_mail is not null)
						        and cen.nro_inscripcion = per.nro_inscripcion) as e_mail,
						alu.calidad,
						year(alu.fecha_ingreso) as anio_ingreso
						from  sga_personas as per 
						left join sga_alumnos as alu on alu.nro_inscripcion = per.nro_inscripcion
						where 1=1";
		if(isset($filtro['nro_documento'])){
			$where[] = 'per.nro_documento = '.quote($filtro['nro_documento']);
		}
		if(isset($filtro['apellido'])){
			$where[] = 'per.apellido = '.quote($filtro['apellido']);
		}
		if(isset($filtro['nombres'])){
			$where[] = 'per.nombres = '.quote($filtro['nombres']);
		}
		if(isset($filtro['legajo'])){
			$where[] = 'alu.legajo = '.quote($filtro['legajo']);
		}
		if(count($where)){
			foreach($where as $condicion){
				$sql .= " AND ".$condicion; 
			}
		}
		return $conexion->consultar($sql);
	}


}

?>