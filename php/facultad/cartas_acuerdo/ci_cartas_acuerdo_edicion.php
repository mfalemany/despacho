<?php
class ci_cartas_acuerdo_edicion extends despacho_ci
{
	//-----------------------------------------------------------------------------------
	//---- fom_carta_acuerdo ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_carta_acuerdo(despacho_ei_formulario $form)
	{
		if($this->get_datos('cartas_acuerdo')->get()){
			$datos = $this->get_datos('cartas_acuerdo')->get();
		}else{
			if($this->get_datos('resoluciones')->get()){
				
				//obtengo la fecha_desde y calculo la fecha hasta
				$resol = $this->get_datos('resoluciones')->get();
				$datos['fecha_desde'] = $resol['fecha'];
				$desde = new DateTime($resol['fecha']);
				
				//duracion habitual de las cartas acuerdo
				$duracion = new DateInterval('P3Y');
				$datos['fecha_hasta'] = $desde->add($duracion)->format('Y-m-d');
				
			}	
		}
		if($datos){
			$form->set_datos($datos);
		}
	}

	function evt__form_carta_acuerdo__modificacion($datos)
	{
		$this->get_datos('cartas_acuerdo')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- form_resolucion --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_resolucion(despacho_ei_formulario $form)
	{
		if($this->get_datos('resoluciones')->get()){
			$form->set_datos($this->get_datos('resoluciones')->get());
		}
		if($this->get_datos('resoluciones')->esta_cargada()){
			$form->set_solo_lectura();
		}
	}

	function evt__form_resolucion__modificacion($datos)
	{
		if( ! $this->get_datos('resoluciones')->esta_cargada()){
			if(toba::consulta_php('co_resoluciones')->existe($datos['nro_resol'],$datos['id_tipo_resolucion'])){
				throw new toba_error('Ya existe la resolución '.$datos['nro_resol'].". Si desea modificarla, vuelva a la pantalla anterior y seleccionela de la lista.");
			}
		}
		$campos[] = array(  'ef'          => 'archivo_pdf',
					 		'nombre'      => $datos['fecha'].'-'.$datos['id_tipo_resolucion'].'-'.$datos['nro_resol'].'.pdf',
					  		'descripcion' => 'Resolucion de Carta Acuerdo');
		toba::consulta_php('helper_archivos')->procesar_campos($campos,$datos,'resoluciones/');
		$this->get_datos('resoluciones')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- ml_instituciones -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__ml_instituciones(despacho_ei_formulario_ml $form_ml)
	{
		if($this->get_datos('partes_acuerdo')->get_filas()){
			$form_ml->set_datos($this->get_datos('partes_acuerdo')->get_filas());
		}
	}

	function evt__ml_instituciones__modificacion($datos)
	{

		$this->get_datos('partes_acuerdo')->procesar_filas($datos);
	}


	function get_datos($tabla = NULL)
	{
		return $this->controlador()->get_datos($tabla);
	}

	function extender_objeto_js()
	{
	}

	function ajax__buscar_resolucion($nro_resol,toba_ajax_respuesta $respuesta)
	{
		$respuesta->set(toba::consulta_php('co_resoluciones')->existe($nro_resol));
	}	
}
?>