<?php
class ci_cartas_acuerdo extends despacho_ci
{
	protected $s__filtro;
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
		$this->set_pantalla('pant_edicion');
	}

	function evt__cancelar()
	{	
		$this->get_datos()->resetear();
		$this->set_pantalla('pant_seleccion');
	}

	function evt__eliminar()
	{
		$this->get_datos()->eliminar();
		$this->get_datos()->resetear();
		$this->set_pantalla('pant_seleccion');

	}

	function evt__guardar()
	{
		try {
			$this->get_datos()->sincronizar();
			$this->get_datos()->resetear();
			$this->set_pantalla('pant_seleccion');
		} catch (toba_error_db $e) {
			toba::notificacion()->agregar('Ocurrió el siguiente error: '.$e->get_mensaje_motor());
		} catch (toba_error_validacion $e) {
			toba::notificacion()->agregar('Ocurrió un error. Asegurese de haber cargado dos o mas partes integrantes','error');
		}
		
	}

	//-----------------------------------------------------------------------------------
	//---- cu_cartas_acuerdo ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cu_cartas_acuerdo(despacho_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		$filtro = (isset($this->s__filtro)) ? $this->s__filtro : array();
		$cuadro->set_datos(toba::consulta_php('co_cartas_acuerdo')->get_cartas_acuerdo($filtro));
	}

	function evt__cu_cartas_acuerdo__seleccion($seleccion)
	{
		$this->get_datos()->cargar($seleccion);
		$this->set_pantalla('pant_edicion');	
	}


	function servicio__ver_pdf()
	{
		$resol = toba::memoria()->get_parametros();
		$resol = $resol['fecha'].'-'.$resol['id_tipo_resolucion'].'-'.$resol['nro_resol'].".pdf";
		$ruta = toba::proyecto()->get_www();
		$ruta = $ruta['path']."resoluciones/".$resol;
		
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename=$resol");
		readfile($ruta);
	}

	function conf_evt__cu_cartas_acuerdo__ver_pdf(toba_evento_usuario $evento, $fila)
	{
		$campos = explode("||",$evento->get_parametros());
		$resol = $campos[2].'-'.$campos[1].'-'.$campos[0].".pdf";
		$ruta = toba::proyecto()->get_www();
		$ruta = $ruta['path']."resoluciones/".$resol;
		if(file_exists($ruta)){
			$evento->mostrar();
		}else{
			$evento->ocultar();
		}
	}


	//-----------------------------------------------------------------------------------
	//---- filtro_cartas_acuerdo --------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_cartas_acuerdo(despacho_ei_formulario $form)
	{
		if(isset($this->s__filtro)){
			$form->set_datos($this->s__filtro);
		}
	}

	function evt__filtro_cartas_acuerdo__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro_cartas_acuerdo__cancelar()
	{
		unset($this->s__filtro);
	}


	function get_datos($tabla = NULL)
	{
		return ($tabla) ? $this->dep('datos')->tabla($tabla) : $this->dep('datos');
	}


}
?>