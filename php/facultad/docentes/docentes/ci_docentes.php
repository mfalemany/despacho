<?php
class ci_docentes extends despacho_ci
{
	protected $s__filtro;


	//-----------------------------------------------------------------------------------
	//---- EVENTOS DEL CI ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	function evt__guardar(){
		try {
			$this->datos()->sincronizar();	
			$this->datos()->resetear();
			toba::notificacion()->agregar('Cambios guardados con exito!','info');
			$this->set_pantalla('pant_lista_docentes');
		} catch (toba_error_db $e) {
			toba::notificacion()->agregar('Ocurrió un error al intentar guardar los datos: '.$e->get_mensaje());
		}catch(Exception $e){
			toba::notificacion()->agregar('Ocurrió un error desconocido: '.$e->getMessage());
		}
	}

	function evt__eliminar()
	{
		$this->dep('datos')->tabla('docentes')->eliminar_todo();
		$this->dep('datos')->resetear();
		$this->set_pantalla('pant_lista_docentes');
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__agregar()
	{
		$this->datos()->resetear();
		$this->set_pantalla('pant_alta_docentes');
	}
	
	function evt__volver(){
		$this->datos()->resetear();
		$this->set_pantalla('pant_lista_docentes');

	}

	//-----------------------------------------------------------------------------------
	//---- cuadro_docentes --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_docentes(despacho_ei_cuadro $cuadro)
	{
		if(isset($this->s__filtro)){
			$cuadro->set_datos(toba::consulta_php('co_docentes')->get_listado($this->s__filtro));
		}else{
			$cuadro->set_datos(toba::consulta_php('co_docentes')->get_listado());
		}
	}
	function evt__cuadro_docentes__seleccion($datos)
	{
		$this->datos()->cargar($datos);
		$this->set_pantalla('pant_alta_docentes');
	}

	function evt__cuadro_docentes__eliminar($datos){
		 //echo "<br><br><br><br><br>lajkshaskljdfal"; var_dump($datos);return;
		try {
			$this->dep('datos')->tabla('personas')->cargar($datos);
			$this->dep('datos')->tabla('docentes')->cargar();
			$this->dep('datos')->tabla('docentes')->eliminar_todo();
			toba::notificacion()->agregar('Docente eliminado con &eacute;xito','info');
			$this->dep('datos')->tabla('docentes')->resetear();	
		} catch (toba_error $e) {
			toba::notificacion()->agregar('No se puede eliminar al docente. Es posible que el docente se encuentre vinculado a una adscripcion','error');
		}
		

		
	}

	//-----------------------------------------------------------------------------------
	//---- filtro_docentes --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_docentes(despacho_ei_formulario $form)
	{
		if(isset($this->s__filtro)){
			$form->set_datos($this->s__filtro);
		}
	}

	function evt__filtro_docentes__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro_docentes__cancelar()
	{
		unset($this->s__filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	function extender_objeto_js()
	{
		echo "
		//---- Eventos ---------------------------------------------
		
		{$this->objeto_js}.evt__agregar = function()
		{
		}
		";
	}

	function datos($tabla = NULL){
		if($tabla){
			return $this->dep('datos')->tabla($tabla);	
		}else{
			return $this->dep('datos');	
		}
		
	}

	

}
?>