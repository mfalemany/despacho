<?php
class ci_proyectos extends despacho_ci
{

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	protected $s__filtro;
	function evt__agregar()
	{
		$this->get_datos()->resetear();
		$this->set_pantalla('edicion_proyecto');
	}

	function evt__cancelar()
	{
		$this->set_pantalla('busqueda_proyecto');
	}

	function evt__eliminar($datos)
	{
		
		$this->get_datos()->eliminar_todo();
		$this->get_datos()->resetear();
		$this->set_pantalla('busqueda_proyecto');

	}

	function evt__guardar()
	{
		$this->get_datos()->sincronizar();
		$this->set_pantalla('busqueda_proyecto');
	}

	//-----------------------------------------------------------------------------------
	//---- filtro_proyecto --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_proyecto(despacho_ei_formulario $filtro)
	{
		$filtro->colapsar();
		return isset($this->s__filtro) ? $this->s__filtro : NULL;
		
	}

	function evt__filtro_proyecto__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro_proyecto__cancelar()
	{
		unset($this->s__filtro);
	}

	function get_datos($tabla = FALSE)
	{
		if($tabla){
			return $this->dep('datos')->tabla($tabla);		
		}else{
			return $this->dep('datos');
		}
		
	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_proyectos -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_proyectos(despacho_ei_cuadro $cuadro)
	{
		$filtro = (isset($this->s__filtro)) ? $this->s__filtro : array(); 
		$datos = toba::consulta_php('co_proyectos')->obtener($filtro);
		$cuadro->set_datos($datos);

	}

	function evt__cuadro_proyectos__seleccion($datos)
	{
		$this->get_datos()->cargar($datos);
		$this->set_pantalla('edicion_proyecto');
	}

	function actualizar_estado_proyecto($id_proyecto=NULL)
	{
		if( ! $id_proyecto){
			return;
		}
		toba::consulta_php('co_proyectos')->actualizar_estado_proyecto($id_proyecto);

	}

	

}
?>