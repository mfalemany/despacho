<?php

require_once toba::instancia()->get_path_proyecto('despacho')."/php/datos/dt_guarani.php";
class ci_edicion_alumnos extends despacho_ci
{
	//-----------------------------------------------------------------------------------
	//---- form_datos_alumno ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_datos_alumno(despacho_ei_formulario $form)
	{
		$alumno = $this->get_datos()->tabla('alumnos')->get();
		if($alumno){
			$alumno['calidad'] = ($alumno['calidad'] == 'E') ? 'Egresado' : 'Alumno';
			$form->set_datos($alumno);
		}
	}

	function evt__form_datos_alumno__modificacion($datos)
	{
		unset($datos['calidad']);
		$this->get_datos()->tabla('alumnos')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form_datos_persona -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_datos_persona(despacho_ei_formulario $form)
	{
		
		if($this->get_datos()->tabla('personas')->get()){
			$form->set_datos($this->get_datos()->tabla('personas')->get());
		}else{
			$this->controlador()->pantalla()->eliminar_evento('eliminar');
			$this->controlador()->pantalla()->eliminar_evento('actualizar_desde_ws');
		}
	}

	function evt__form_datos_persona__modificacion($datos)
	{
		$this->get_datos()->tabla('personas')->set($datos);
	}

	function get_datos(){
		return $this->controlador()->dep('datos');
	}

}

?>