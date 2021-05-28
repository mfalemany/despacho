<?php
class ci_catedras extends despacho_ci
{
	//-----------------------------------------------------------------------------------
	//---- form_catedra1 ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_catedra(despacho_ei_formulario $form)
	{
		//si está cargada la tabla de catedras (es porque se seleccionó una del cuadro)
		if($this->dep('datos')->tabla('catedras')->esta_cargada()){
			$form->set_datos($this->dep('datos')->tabla('catedras')->get());
		}
		
	}

	function evt__form_catedra__guardar($datos)
	{
		$this->dep('datos')->tabla('departamentos')->cargar(
			array('id_departamento'=>$datos['id_departamento'])
			);
		$this->dep('datos')->tabla('catedras')->set($datos);
		//var_dump($this->dep('datos')->tabla('catedras')->get()); die;

		$this->dep('datos')->tabla('catedras')->sincronizar();
		$this->dep('datos')->resetear();
		
	}
	function evt__form_catedra__borrar($seleccion)
	{
		//obtengo el ID del departamento de la catedra seleccionada (eso se carga para
		// que al actualizar o borrar el registro de catedras no de error referencial)

		$this->dep('datos')->tabla('departamentos')->cargar(
			array('id_departamento'=>$seleccion['id_departamento']));
		
		/*una vez cargado el departamento en el datos_tabla, 
		/ procedo a eliminar el registro de la catedra */
		$this->dep('datos')->tabla('catedras')->cargar($seleccion);
		$this->dep('datos')->tabla('catedras')->eliminar_todo();
		$this->dep('datos')->resetear();
	}

	function evt__form_catedra__cancelar(){
		$this->dep('datos')->resetear();
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_catedras1 ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	function conf__cuadro_catedras(despacho_ei_cuadro $cuadro)
	{
		$cuadro->set_datos(toba::consulta_php('co_catedras')->get_listado());
	}

	function evt__cuadro_catedras__modificar($seleccion)
	{
		$dpto = toba::consulta_php('co_catedras')->get_departamento($seleccion['id_catedra']);
		$this->dep('datos')->tabla('departamentos')->cargar($dpto[0]);
		$this->dep('datos')->tabla('catedras')->cargar($seleccion);
	}

	

}

?>