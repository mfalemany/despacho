<?php
class ci_edicion_adscripciones extends despacho_ci
{
	//-----------------------------------------------------------------------------------
	//------------------------ PANTALLA DE ALTA/EDICION----------------------------------
	//-----------------------------------------------------------------------------------
	
	//-----------------------------------------------------------------------------------
	//---- form_adscripcion_resolucion --------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_adscripcion_resolucion(despacho_ei_formulario $form)
	{
		$datos = $this->get_datos()->tabla('resoluciones')->get();
		if($datos){
			$form->set_datos($datos);
		}
		if($this->get_datos()->tabla('resoluciones')->esta_cargada()){
			$form->set_solo_lectura();
		}
	}

	//evento implicito que se dispara al producirse el evento guardar del CI
	function evt__form_adscripcion_resolucion__guardar($datos)
	{	
		if( ! $this->get_datos()->esta_cargada()){
			if(toba::consulta_php('co_resoluciones')->existe($datos['nro_resol'],$datos['id_tipo_resolucion'])){
				throw new toba_error('Ya existe la resolución '.$datos['nro_resol'].". Si desea modificarla, vuelva a la pantalla anterior y seleccionela de la lista.");
			}
		}
		$this->get_datos()->tabla('resoluciones')->set($datos);
	}


	//-----------------------------------------------------------------------------------
	//---- cuadro_adscripciones ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_adscripciones(despacho_ei_cuadro $cuadro)
	{
		if($this->get_datos()->tabla('adscripciones')->get_filas()){
			$resol = $this->get_datos()->tabla('resoluciones')->get();
			$cuadro->set_datos(toba::consulta_php('co_adscripciones')->get_adscripciones_descripciones($resol['nro_resol']));
		}
	}

	function evt__cuadro_adscripciones__eliminar($seleccion)
	{
		$adscripcion = $this->get_datos()->tabla('adscripciones')->get_filas($seleccion,true);
		$this->get_datos()->tabla('adscripciones')->eliminar_fila(key($adscripcion));
	}

	function evt__cuadro_adscripciones__modificar($seleccion)
	{
		$fila = $this->get_datos()->tabla('adscripciones')->get_filas($seleccion,true);
		$this->get_datos()->tabla('adscripciones')->set_cursor(key($fila));
	}

	
	//-----------------------------------------------------------------------------------
	//---- form_adscripcion -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_adscripcion(despacho_ei_formulario $form)
	{
		if($this->get_datos()->tabla('adscripciones')->hay_cursor()){
			$form->set_datos($this->get_datos()->tabla('adscripciones')->get());
		}else{
			$detalles_resol = $this->get_datos()->tabla('resoluciones')->get();
			$inicio = new DateTime($detalles_resol['fecha']);
			$form->set_datos(array('fecha_inicio'=>$inicio->format('Y-m-d'),'fecha_fin'=>$inicio->add(new DateInterval('P2Y'))->format('Y-m-d')));
		}
	}

	function evt__form_adscripcion__guardar($datos)
	{
		$this->get_datos()->tabla('adscripciones')->set($datos);
		$this->get_datos()->sincronizar();
		$this->evt__form_adscripcion__limpiar();
	}

	function evt__form_adscripcion__limpiar()
	{
		$this->get_datos()->tabla('adscripciones')->resetear_cursor();
		$this->dep('form_adscripcion')->limpiar_interface();
	}

	//-----------------------------------------------------------------------------------
	//----------------------------- EVENTOS DEL CI---------------------------------------
	//-----------------------------------------------------------------------------------

	
	function get_ayn($nro_documento){
		return toba::consulta_php('co_personas')->get_ayn($nro_documento);
	}
	function get_ayn_docente($legajo){
		return toba::consulta_php('co_docentes')->get_ayn($legajo);
	}

	function get_datos(){
		return $this->controlador()->get_datos();
	}

	function extender_objeto_js()
    {
    	
    }   

    function ajax__existe_resolucion($nro_resol, toba_ajax_respuesta $respuesta)
    {
    	$resultado = toba::consulta_php('co_resoluciones')->existe($nro_resol);
    	$respuesta->set(array('existe'=>$resultado));
    }
}
?>