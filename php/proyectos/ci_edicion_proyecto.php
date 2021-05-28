<?php
class ci_edicion_proyecto extends despacho_ci
{
	protected $s__estado_proyecto;

	function conf()
	{
		if( ! $this->get_datos('proyecto')->esta_cargada()){
			$this->pantalla('edicion_proyecto')->eliminar_tab('pant_evaluaciones');
		}

	}

	//-----------------------------------------------------------------------------------
	//---- form_proyecto ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_proyecto(despacho_ei_formulario $form)
	{
		$datos = $this->get_datos('proyecto')->get();
		if($datos){
			$datos['nro_resol'] = array($datos['id_tipo_resolucion'],$datos['nro_resol']);
			$form->set_datos($datos);

			//si el alumno ya egresó, se bloquea el formulario y se eliminan eventos
			if(in_array($datos['estado'],array('E','R'))){
				$this->s__estado_proyecto = $datos['estado'];
				$this->bloquear_formulario($form);
			}else{
				//si el proyecto está aceptado y listo para rendir, solo se puede 
				//modificar el estado. Todos los demás campos se bloquean
				if($datos['estado'] == 'A'){
					$this->s__estado_proyecto = $datos['estado'];
					$form->agregar_notificacion("El proyecto está listo para rendir. Solo puede modificar el estado del proyecto y/o agregar comentarios.",'warning');
					$form->set_solo_lectura(array('nro_resol','alu_legajo','asesor_dni','id_modalidad','tema'));
					$this->controlador()->pantalla()->eliminar_evento('eliminar');
				}else{
					unset($this->s__estado_proyecto);	
				}
				
			}

		}else{
			$this->controlador()->pantalla()->eliminar_evento('eliminar');
			$form->ef('estado')->set_estado('P');
		}
		
	}

	function evt__form_proyecto__modificacion($datos)
	{
		if(toba::consulta_php('co_proyectos')->es_asesor_nuevo($datos['asesor_dni'])){
			toba::notificacion()->agregar("El asesor seleccionado es nuevo, debe solicitarle el CV.",'warning');
		}
		$datos['id_tipo_resolucion'] = $datos['nro_resol'][0];
		$datos['nro_resol'] = $datos['nro_resol'][1];
		$this->get_datos('proyecto')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- ml_tribunal ------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__ml_tribunal(despacho_ei_formulario_ml $form_ml)
	{
		$datos = $this->get_datos('evaluador')->get_filas();
		if($datos){
			$form_ml->set_datos($datos);
			if(isset($this->s__estado_proyecto) && in_array($this->s__estado_proyecto,array('A','E','R'))){
				$this->bloquear_formulario($form_ml);
				$this->controlador()->pantalla()->eliminar_evento('eliminar');
			}
		}
	}

	function evt__ml_tribunal__modificacion($datos)
	{
		$this->get_datos('evaluador')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_evaluaciones ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_evaluaciones(despacho_ei_cuadro $cuadro)
	{
		$proyecto = $this->get_datos('proyecto')->get();
		$cuadro->set_datos(toba::consulta_php('co_evaluacion_proyecto')->get_evaluaciones($proyecto['id_proyecto']));

	}

	function conf_evt__cuadro_evaluaciones__borrar($evento,$fila){
		
		if(isset($this->s__estado_proyecto) && in_array($this->s__estado_proyecto,array('A','E','R'))){
			$evento->ocultar();
		}
	}
	function evt__cuadro_evaluaciones__borrar($seleccion)
	{
		$proyecto = $this->get_datos('proyecto')->get();
		$id = $this->get_datos('evaluacion_proyecto')->get_id_fila_condicion($seleccion);
		$this->get_datos('evaluacion_proyecto')->eliminar_fila($id[0]);
		$this->get_datos()->sincronizar();
		//Actualizo el estado del proyecto
		$this->controlador()->actualizar_estado_proyecto($proyecto['id_proyecto']);
		$this->set_pantalla('pant_evaluaciones');
	}

	//-----------------------------------------------------------------------------------
	//---- form_evaluacion --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_evaluacion(despacho_ei_formulario $form)
	{
		$form->ef('fecha')->set_estado(date('Y-m-d'));
		if($this->get_datos('proyecto')->get()){
			$proyecto = $this->get_datos('proyecto')->get();
			$form->ef('legajo')->set_opciones(toba::consulta_php('co_evaluador')->get_evaluadores($proyecto['id_proyecto']));
			if(isset($this->s__estado_proyecto) && in_array($this->s__estado_proyecto,array('A','E','R')) ){
				$this->bloquear_formulario($form);
				$form->eliminar_evento('guardar');
			}

		}
		
	}

	function evt__form_evaluacion__guardar($datos)
	{
		$this->get_datos('evaluacion_proyecto')->nueva_fila($datos);
		$this->get_datos()->sincronizar();
		$this->set_pantalla('pant_evaluaciones');
		$proyecto = $this->get_datos('proyecto')->get();
		$this->controlador()->actualizar_estado_proyecto($proyecto['id_proyecto']);
	}

	private function get_datos($tabla='')
	{
		return $this->controlador()->get_datos($tabla);
	}

	function get_nro_resol($nro_resol){
		return "Resol. ".$nro_resol;
	}

	function ajax__existe_proyecto($nro_resol,toba_ajax_respuesta $respuesta)
	{
		$resultado = toba::consulta_php('co_proyectos')->obtener(array('nro_resol' => $nro_resol));
		$respuesta->set(array('encontrado'=>$resultado));
	}

	private function bloquear_formulario(&$form)
	{
		$form->set_solo_lectura(NULL,true);
		//ei_arbol($this->controlador()->pantalla());
		$this->controlador()->pantalla()->eliminar_evento('guardar');
		$this->controlador()->pantalla()->eliminar_evento('eliminar');
	}

	function ajax__get_posibles_resultados_por_evaluador($params,toba_ajax_respuesta $respuesta)
	{
		$ultima_evaluacion = toba::consulta_php('co_evaluacion_proyecto')->get_posibles_resultados_por_evaluador($params['id_proyecto'],$params['legajo']);
		switch ($ultima_evaluacion) {
			//si el docente todavia no evaluó el proyecto, puede elegir cualquiera de las opciones
			case NULL:
				$opc = array('nopar'=>'--Seleccione--','M'=>'Solicita Modificaciones','A'=>"Aceptado","R"=>"Rechazado");
				break;
			//si el docente ya había aceptado el proyecto, solo puede aceptar las modificaciones o rechazarlo	
			case 'A':
				$opc = array('nopar'=>'--Seleccione--','C'=>'Acepta modificaciones realizadas','R'=>'Rechazado');
				break;
			//si el docente ya rechazó el proyecto, no puede hacer mas nada	
			case 'R':
				$opc = array();
				break;
			default:
				$opc = array('nopar'=>'--Seleccione--','M'=>'Solicita Modificaciones','A'=>"Aceptado","R"=>"Rechazado");
				break;
		}
		
		//esto se hace para que el servidor no rechace las opciones enviadas desde el cliente (opciones que fueron cargadas 
		//por ajax)
		//====================================================
		$this->dep('form_evaluacion')->ef('resultado')->set_opciones($opc);
		//====================================================
		
		//se envía la respuesta ajax
		$respuesta->set($opc);
	}

}
?>