<?php
class ci_reporte_adscripciones extends despacho_ci
{
	protected $s__filtro;
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__generar_reporte()
	{

	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_adscripciones ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_adscripciones(despacho_ei_cuadro $cuadro)
	{
		if(isset($this->s__filtro)){
			$cuadro->set_datos(toba::consulta_php('co_adscripciones')->get_reporte($this->s__filtro));
		}else{
			$cuadro->set_datos(toba::consulta_php('co_adscripciones')->get_reporte());
		}
	}

	//-----------------------------------------------------------------------------------
	//---- filtro_reporte ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_reporte(despacho_ei_formulario $form)
	{
		if(isset($this->s__filtro)){
			$form->set_datos($this->s__filtro);
		}
	}

	function evt__filtro_reporte__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro_reporte__cancelar()
	{
		unset($this->s__filtro);
	}


	//Descripciones para respuesta POPUP
	function get_ayn($nro_documento){
		return toba::consulta_php('co_personas')->get_ayn($nro_documento,true);
	}

	function get_ayn_docente($legajo){
		return toba::consulta_php('co_docentes')->get_ayn($legajo);
	}
}
?>