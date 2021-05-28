<?php
class ci_tipos_de_resolucion extends despacho_ci
{
	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		$cuadro->set_datos(toba::consulta_php('co_tipos_resolucion')->get_listado());
	}

	function evt__cuadro__eliminar($datos)
	{
		$this->dep('datos')->tabla('tipos_resolucion')->resetear();
		$this->dep('datos')->tabla('tipos_resolucion')->cargar($datos);
		$this->dep('datos')->tabla('tipos_resolucion')->eliminar_todo();
		$this->dep('datos')->tabla('tipos_resolucion')->resetear();
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->tabla('tipos_resolucion')->cargar($datos);
	}

	//---- Formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		if ($this->dep('datos')->tabla('tipos_resolucion')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('tipos_resolucion')->get());
		}
	}

	function evt__formulario__alta($datos)
	{
		$this->dep('datos')->tabla('tipos_resolucion')->set($datos);
		$this->dep('datos')->tabla('tipos_resolucion')->sincronizar();
		$this->resetear();
	}

	function evt__formulario__modificacion($datos)
	{
		$this->dep('datos')->tabla('tipos_resolucion')->set($datos);
		$this->dep('datos')->tabla('tipos_resolucion')->sincronizar();
		$this->resetear();
	}

	function evt__formulario__baja()
	{
		$this->dep('datos')->tabla('tipos_resolucion')->eliminar_todo();
		$this->resetear();
	}

	function evt__formulario__cancelar()
	{
		$this->resetear();
	}

	function resetear()
	{
		$this->dep('datos')->tabla('tipos_resolucion')->resetear();
	}

}

?>