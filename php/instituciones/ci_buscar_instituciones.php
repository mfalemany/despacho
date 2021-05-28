<?php
class ci_buscar_instituciones extends despacho_ci
{
	protected $s__filtro;
	//-----------------------------------------------------------------------------------
	//---- cu_instituciones_busqueda ----------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cu_instituciones_busqueda(despacho_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		$filtro = isset($this->s__filtro) ? $this->s__filtro : array();
		$cuadro->set_datos(toba::consulta_php('co_instituciones')->get_instituciones($filtro));
	}
	//-----------------------------------------------------------------------------------
	//---- filtro_instituciones ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_instituciones(despacho_ei_formulario $form)
	{
		if(isset($this->s__filtro)){
			$form->set_datos($this->s__filtro);
		}	

	}

	function evt__filtro_instituciones__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro_instituciones__cancelar()
	{
		unset($this->s__filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- form_instituciones -----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__form_instituciones__alta($datos)
	{
		$this->dep('instituciones')->set($datos);
		$this->dep('instituciones')->sincronizar();
		$this->dep('instituciones')->resetear();

	}
}
?>