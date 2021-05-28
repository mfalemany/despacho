<?php
class ci_resoluciones extends despacho_ci
{
	protected $s__datos_filtro;

	//---- Filtro -----------------------------------------------------------------------

	function conf__filtro(toba_ei_formulario $filtro)
	{
		if (isset($this->s__datos_filtro)) {
			$filtro->set_datos($this->s__datos_filtro);
		}
	}

	function evt__filtro__filtrar($datos)
	{
		$this->s__datos_filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__datos_filtro);
	}

	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		if (isset($this->s__datos_filtro)) {
			$cuadro->set_datos(toba::consulta_php('co_resoluciones')->get_listado($this->s__datos_filtro));
		} else {
			$cuadro->set_datos(toba::consulta_php('co_resoluciones')->get_listado());
		}
	}
	
	function conf_evt__cuadro__ver_pdf(toba_evento_usuario $evento, $fila)
	{
		$params = explode('||',$evento->get_parametros());
		//obtengo (si tiene) el nombre del archivo PDF correspondiente
		$pdf = toba::consulta_php('co_resoluciones')->get_nombre_pdf($params[0],$params[1]);

		//si el archivo no existe, o no tiene asignado un nombre de archivo, se oculta el evento
		if( ! (file_exists(toba::proyecto()->get_www()['path']."resoluciones/".$pdf)) || ( ! $pdf)){
			$evento->ocultar();	
		}else{
			$evento->mostrar();
		}
		
		
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->cargar($datos);
		$this->set_pantalla('pant_edicion');
	}

	//---- Formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		if ($this->dep('datos')->tabla('resoluciones')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('resoluciones')->get());
		} else {
			$this->pantalla()->eliminar_evento('eliminar');
		}
	}

	function evt__formulario__modificacion($datos)
	{
		if ($this->dep('formulario')->ef('archivo_pdf')->get_estado_input()) {
			//asigno el nombre que le corresponde al PDF ("aÃ±o"-"mes"-"dia"-"id_tipo_resol"-"nro_resol".pdf)
			$nombre_archivo = $datos['fecha'].'-'.$datos['id_tipo_resolucion']."-".$datos['nro_resol'].".pdf";
			$img = toba::proyecto()->get_www()['path']."/resoluciones/".$nombre_archivo;
			move_uploaded_file($datos['archivo_pdf']['tmp_name'], $img);
			$datos['archivo_pdf'] = $nombre_archivo;
		}else{
			unset($datos['archivo_pdf']);
		}
		$this->dep('datos')->tabla('resoluciones')->set($datos);
		
	}

	function resetear()
	{
		$this->dep('datos')->resetear();
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
		$this->dep('datos')->tabla('resoluciones')->eliminar_todo();
		$this->resetear();
	}

	function evt__guardar()
	{
		$this->dep('datos')->sincronizar();
		$this->resetear();
	}

	function servicio__ver_pdf()
	{
		$parametros = toba::memoria()->get_parametros();
		$nombre_pdf = toba::consulta_php('co_resoluciones')->get_nombre_pdf($parametros['id_tipo_resolucion'],$parametros['nro_resol']);
		
		if(file_exists(toba::proyecto()->get_www()['path']."/resoluciones/".$nombre_pdf)){
			//muestro el PDF
			header("Location: resoluciones/".$nombre_pdf);	
		}else{
			throw new toba_error('La resolución no tiene documento digital','error');
			//header("Location: ".toba::proyecto()->get_www()['url']);
		}
		
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------


	//-----------------------------------------------------------------------------------
	//---- modifica_a -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__modifica_a(despacho_ei_formulario_ml $form_ml)
	{
		$datos = $this->dep('datos')->tabla('modifica_resolucion')->get_filas();
		if($datos){
			foreach ($datos as $clave => $resol) {
				$datos[$clave]['resol_modifica'] = array($resol['id_tipo_resol_modif'],$resol['nro_resol_modifica']);
			}
			$form_ml->set_datos($datos);	
		}
	}

	function evt__modifica_a__guardar($datos)
	{
		if($datos){
			foreach ($datos as $clave => $resol) {
				if(array_key_exists('resol_modifica', $datos[$clave])){
					$datos[$clave]['id_tipo_resol_modif'] = $resol['resol_modifica'][0];
					$datos[$clave]['nro_resol_modifica']  = $resol['resol_modifica'][1];		
				}
			}
			
		}
		$this->dep('datos')->tabla('modifica_resolucion')->procesar_filas($datos);
	}

	

	//-----------------------------------------------------------------------------------
	//---- es_modificada_por ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__es_modificada_por(despacho_ei_formulario_ml $form_ml)
	{
		$original = $this->dep('datos')->tabla('resoluciones')->get();
		if($original){
			$form_ml->set_datos(toba::consulta_php('co_modifica_resolucion')->get_resol_modificadoras($original['nro_resol'],$original['id_tipo_resolucion']));	
		}

		
	}

}
?>