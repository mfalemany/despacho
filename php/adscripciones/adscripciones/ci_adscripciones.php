<?php
class ci_adscripciones extends despacho_ci
{
	protected $s__filtro;

	//-----------------------------------------------------------------------------------
	//------------------------ PANTALLA DE SELECCION ------------------------------------
	//-----------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------
	//---- filtro_resoluciones_adscripcion ----------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_resoluciones_adscripcion(despacho_ei_formulario $form)
	{
		if(isset($this->s__filtro)){
			$form->set_datos($this->s__filtro);
		}
	}

	function evt__filtro_resoluciones_adscripcion__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro_resoluciones_adscripcion__cancelar()
	{
		unset($this->s__filtro);
	}
	//-----------------------------------------------------------------------------------
	//---- resoluciones_adscripcion -----------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__resoluciones_adscripcion(despacho_ei_cuadro $cuadro)
	{
		if(isset($this->s__filtro)){
			$cuadro->set_datos(toba::consulta_php('co_adscripciones')->get_resoluciones_adscripcion($this->s__filtro));
		}else{
			$cuadro->set_datos(toba::consulta_php('co_adscripciones')->get_resoluciones_adscripcion());
		}
	}

	function evt__resoluciones_adscripcion__seleccion($seleccion)
	{
		//cargo la resolucion seleccionada
		$this->get_datos()->cargar($seleccion);
		//voy a la pantalla de edicion
		$this->set_pantalla('pant_adscripcion');
	}

	function evt__agregar()
	{
		$this->get_datos()->resetear();
		$this->set_pantalla('pant_adscripcion');
	}

	function evt__guardar()
	{	
		try{
			$ads = $this->get_datos('adscripciones')->get_filas();
			if(count($ads) == 0){
				throw new toba_error("Para guardar una resolución debe cargarle al menos una adscripción");
			}
			$this->get_datos()->sincronizar();
			$this->get_datos()->resetear();
			$this->set_pantalla('pant_lista_adscripciones');
		}catch(toba_error $e){
			toba::notificacion()->agregar("Ocurrió un error: ".$e->get_mensaje());
		}
		
	}
	function evt__cancelar(){
		$this->get_datos()->resetear();
		$this->set_pantalla('pant_lista_adscripciones');
	}

	function get_datos($tabla = NULL){
		return ($tabla) ? $this->dep('datos')->tabla($tabla) : $this->dep('datos');
	}

	
	

}
?>