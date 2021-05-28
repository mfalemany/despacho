<?php
class ci_personas extends despacho_ci
{
	protected $s__datos_filtro;

	//---- Filtro -----------------------------------------------------------------------

	function conf__filtro_personas(toba_ei_formulario $filtro)
	{
		if (isset($this->s__datos_filtro)) {
			$filtro->set_datos($this->s__datos_filtro);
		}
		/*
		$sql = "select first 67 nombre from sga_materias";
		$dsn = "Driver={IBM INFORMIX ODBC DRIVER (64-bit)}; Server=ol_agrarias; Database=agr_v293;";
		$conexion = odbc_connect($dsn,'dba','jz012699');
		$resultado = odbc_exec($conexion,$sql);
		$i = 0;
		while($registro = odbc_fetch_array($resultado) ){
			echo "insert into adscripciones.catedras values (".$i.",'".$registro['nombre']."',12);<br>";
			$i++;
		}
		
		$sql = "select per.nro_documento, per.apellido, per.nombres, per.fecha_nacimiento, cen.e_mail, cen.te_per_lect, alu.legajo, alu.nro_inscripcion, year(alu.fecha_ingreso) as anio
				from sga_personas as per
				left join sga_alumnos as alu on alu.nro_inscripcion = per.nro_inscripcion
				left join sga_datos_censales as cen on cen.nro_inscripcion = per.nro_inscripcion and cen.fecha_relevamiento = (select max(fecha_relevamiento) from sga_datos_censales where nro_inscripcion = per.nro_inscripcion)";
		
		$dsn = "Driver={IBM INFORMIX ODBC DRIVER (64-bit)}; Server=ol_agrarias; Database=agr_v293;";
		$conexion = odbc_connect($dsn,'dba','jz012699');
		$resultado = odbc_exec($conexion,$sql);

		while($registro = odbc_fetch_array($resultado) ){
			if(strlen($registro['nro_documento'])>10){
				continue;
			}
			if(isset($registro['fecha_nacimiento'])){
				if(strpos($registro['fecha_nacimiento'],"-")){
					$f = explode("-",$registro['fecha_nacimiento']);
				}else{
					$f = explode("/",$registro['fecha_nacimiento']);
				}

			}else{
				$f = array('01','01','1900');
			}
			//echo "<br><br><br><br>";
			//var_dump($f); return;
			$fecha = $f[2]."-".$f[1]."-".$f[0];
			//echo $fecha; return;
			echo "insert into adscripciones.personas values ('".str_replace(".","",$registro['nro_documento'])."','".$registro['apellido']."','".$registro['nombres']."','".$fecha."','".$registro['te_per_lect']."','".$registro['e_mail']."');<br>";
			echo "insert into adscripciones.alumnos values ('".$registro['nro_inscripcion']."','".$registro['legajo']."','".str_replace(".","",$registro['nro_documento'])."',".$registro['anio'].");<br>";
				
		}return;*/
	}

	function evt__filtro_personas__filtrar($datos)
	{
		$this->s__datos_filtro = $datos;
	}

	function evt__filtro_personas__cancelar()
	{
		unset($this->s__datos_filtro);
	}

	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__datos_filtro)) {
			$cuadro->set_datos(toba::consulta_php('co_personas')->get_listado($this->s__datos_filtro));
		} else {
			$cuadro->set_datos(toba::consulta_php('co_personas')->get_listado());
		}
	}

	function evt__cuadro__eliminar($datos)
	{
		$this->dep('datos')->tabla('personas')->resetear();
		$this->dep('datos')->tabla('personas')->cargar($datos);
		$this->dep('datos')->tabla('personas')->eliminar_todo();
		$this->dep('datos')->tabla('personas')->resetear();
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->tabla('personas')->cargar($datos);
		$this->set_pantalla('pant_edicion');
	}

	//---- Formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		if ($this->dep('datos')->tabla('personas')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('personas')->get());
		} else {
			$this->pantalla()->eliminar_evento('eliminar');
		}
	}

	function evt__formulario__modificacion($datos)
	{
		$this->dep('datos')->tabla('personas')->set($datos);
	}

	function resetear()
	{
		$this->dep('datos')->tabla('personas')->resetear();
		$this->set_pantalla('pant_seleccion');
	}

	//---- EVENTOS CI -------------------------------------------------------------------

	function evt__agregar()
	{
		$this->set_pantalla('pant_edicion');
	}

	function evt__volver()
	{
		$this->resetear();
	}

	function evt__eliminar()
	{
		$this->dep('datos')->tabla('personas')->eliminar_todo();
		$this->resetear();
	}

	function evt__guardar()
	{
		$this->dep('datos')->sincronizar();
		$this->resetear();
		$this->controlador()->set_pantalla('pant_seleccion');
	}

	function extender_objeto_js()
	{
		
	}
}

?>