<?php
class ci_dedicaciones extends despacho_ci
{
	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		$cuadro->set_datos(toba::consulta_php('co_dedicaciones')->get_listado());
	}

	function evt__cuadro__eliminar($datos)
	{
		$this->dep('datos')->tabla('dedicaciones')->resetear();
		$this->dep('datos')->tabla('dedicaciones')->cargar($datos);
		$this->dep('datos')->tabla('dedicaciones')->eliminar_todo();
		$this->dep('datos')->tabla('dedicaciones')->resetear();
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->tabla('dedicaciones')->cargar($datos);
	}

	//---- Formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		if ($this->dep('datos')->tabla('dedicaciones')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('dedicaciones')->get());
		}
	}

	function evt__formulario__alta($datos)
	{
		$this->dep('datos')->tabla('dedicaciones')->set($datos);
		$this->dep('datos')->tabla('dedicaciones')->sincronizar();
		$this->resetear();
	}

	function evt__formulario__modificacion($datos)
	{
		$this->dep('datos')->tabla('dedicaciones')->set($datos);
		$this->dep('datos')->tabla('dedicaciones')->sincronizar();
		$this->resetear();
	}

	function evt__formulario__baja()
	{
		$this->dep('datos')->tabla('dedicaciones')->eliminar_todo();
		$this->resetear();
	}

	function evt__formulario__cancelar()
	{
		$this->resetear();
	}

	function resetear()
	{
		$this->dep('datos')->tabla('dedicaciones')->resetear();
	}

}

?>