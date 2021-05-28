<?php
class ci_cargos extends despacho_ci
{
	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		$cuadro->set_datos(toba::consulta_php('co_cargos')->get_listado());
	}

	function evt__cuadro__eliminar($datos)
	{
		$this->dep('datos')->tabla('cargos')->resetear();
		$this->dep('datos')->tabla('cargos')->cargar($datos);
		$this->dep('datos')->tabla('cargos')->eliminar_todo();
		$this->dep('datos')->tabla('cargos')->resetear();
	}

	function evt__cuadro__seleccion($datos)
	{
		//var_dump($datos); return;
		$this->dep('datos')->tabla('cargos')->cargar($datos);
	}

	//---- Formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		//var_dump($this->dep('datos')->tabla('cargos')->esta_cargada());
		if ($this->dep('datos')->tabla('cargos')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('cargos')->get());
		}
	}

	function evt__formulario__alta($datos)
	{
		$this->guardar_cargo($datos);	
	}

	function evt__formulario__modificacion($datos)
	{
		$this->guardar_cargo($datos);
	}
	
	private function guardar_cargo($datos){
		if( ! $datos['cargo']){
			return false;
		}
		$this->dep('datos')->tabla('cargos')->set($datos);
		$this->dep('datos')->tabla('cargos')->sincronizar();
		$this->resetear();
	}

	function evt__formulario__baja()
	{
		$this->dep('datos')->tabla('cargos')->eliminar_todo();
		$this->resetear();
	}

	function evt__formulario__cancelar()
	{
		$this->resetear();
	}

	function resetear()
	{
		$this->dep('datos')->resetear();
	}

}

?>