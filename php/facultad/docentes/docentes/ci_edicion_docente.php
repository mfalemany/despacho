<?php
class ci_edicion_docente extends despacho_ci
{
	function conf(){
		if( ! $this->datos('personas')->get()){
			$this->controlador()->pantalla()->eliminar_evento('eliminar');	
		}
	}
	
	

	//-----------------------------------------------------------------------------------
	//---- form_datos_personales --------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_datos_personales(despacho_ei_formulario $form)
	{
		if($this->datos('personas')->get()){
			$form->set_datos($this->datos('personas')->get());
			$form->set_solo_lectura(array('nro_documento','apellido','nombres'));
		}
	}

	function evt__form_datos_personales__modificacion($datos)
	{
		if(!$this->datos()->esta_cargada()){
			$this->datos()->cargar(array('nro_documento'=>$datos['nro_documento']));
			if($this->datos()->esta_cargada()){
				unset($datos['nro_documento']);
				unset($datos['apellido']);
				unset($datos['nombres']);

			}
		}
		$this->datos('personas')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form_datos_docente -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_datos_docente(despacho_ei_formulario $form)
	{
		if($this->datos('docentes')->get()){
			$form->set_datos($this->datos('docentes')->get());
		}
	}

	function evt__form_datos_docente__modificacion($datos){

		$this->datos('docentes')->set($datos);     
	}

	//-----------------------------------------------------------------------------------
	//---- ml_cargos --------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__ml_cargos(despacho_ei_formulario_ml $form)
	{
		if($this->datos('docentes_cargos')->get_filas()){
			$form->set_datos($this->datos('docentes_cargos')->get_filas());
		}
	}

	function evt__ml_cargos__modificacion($datos)
	{
		$this->datos('docentes_cargos')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//----  ------------ --------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function datos($tabla = ''){
		if($tabla){
			return $this->controlador()->datos($tabla);    
		}else{
			return $this->controlador()->datos();    
		}
	}
}
?>