<?php
class ci_alumnos extends despacho_ci
{
	protected $s__datos_filtro;


	//---- Filtro -----------------------------------------------------------------------

	function conf__filtro(toba_ei_formulario $filtro)
	{
		if (isset($this->s__datos_filtro)) {
			$filtro->set_datos($this->s__datos_filtro);
		}
	}

	function evt__filtro__filtrar($datos)
	{
		$this->s__datos_filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__datos_filtro);
	}

	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__datos_filtro)) {
			$cuadro->set_datos(toba::consulta_php('co_alumnos')->get_listado($this->s__datos_filtro));
		} else {
			$cuadro->set_datos(toba::consulta_php('co_alumnos')->get_listado());
		}
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->tabla('personas')->cargar(array('nro_documento'=>$datos['nro_documento']));
		$this->dep('datos')->tabla('alumnos')->cargar(array('legajo'=>$datos['legajo']) );
		$this->set_pantalla('pant_edicion');
	}

	function resetear()
	{
		$this->dep('datos')->resetear();
		$this->set_pantalla('pant_seleccion');
	}

	//---- EVENTOS CI -------------------------------------------------------------------

	function evt__agregar()
	{
		$this->set_pantalla('pant_edicion');
	}

	function evt__volver()
	{
		$this->resetear();
	}

	function evt__eliminar()
	{
		try{
			$this->dep('datos')->tabla('alumnos')->eliminar_todo();
			$this->resetear();
		}catch(Exception $e){
			toba::notificacion()->agregar('No se puede eliminar el alumno. Probablemente se encuentre vinculado a una adscripción y/o esté; registrado como docente');
		}
		
	}

	function evt__guardar()
	{
		$this->dep('datos')->sincronizar();
		$this->resetear();
	}

	function evt__actualizar_desde_ws()
	{

		$persona = $this->dep('datos')->tabla('personas')->get();
		if(!$persona){
			return;
		}
		
		$datos = toba::consulta_php('co_personas')->buscar_en_guarani(array('nro_documento'=>$persona['nro_documento']));
		if(array_key_exists(0, $datos)){
			$datos = $datos[0];
		}
		$datos['mail'] = $datos['e_mail'];
		unset($datos['nro_documento']);
		$datos['fecha_nac'] = $datos['fecha_nacimiento'];
		$datos['telefono'] = $datos['celular_numero'];

		$this->dep('datos')->tabla('personas')->set($datos);
		toba::notificacion()->agregar('Sincronizado con éxito! No olvide guardar los cambios.','info');

	}
}

?>