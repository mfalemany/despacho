<?php
class ci_reporte_proyectos extends despacho_ci
{
	protected $s__filtro;

	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro(despacho_ei_cuadro $cuadro)
	{
		if(isset($this->s__filtro)){
			$cuadro->set_datos(toba::consulta_php('co_proyectos')->get_proyectos_por_evaluador($this->s__filtro['legajo']));
		}else{
			$cuadro->set_datos(toba::consulta_php('co_proyectos')->get_proyectos_por_evaluador());
		}
	}

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(despacho_ei_formulario $form)
	{
		if(isset($this->s__filtro)){
			$form->set_datos($this->s__filtro);
		}
	}

	function evt__formulario__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__formulario__cancelar()
	{
		unset($this->s__filtro);
	}

}
?>