<?php
class ci_buscar_alumnos extends despacho_ci
{
	protected $s__datos_filtro;

	function conf__cuadro_alumnos(despacho_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		if(isset($this->s__datos_filtro)){
			$cuadro->set_datos(toba::consulta_php('co_alumnos')->get_listado($this->s__datos_filtro,true));
		}else{
			$cuadro->set_datos(toba::consulta_php('co_alumnos')->get_listado(array(),true));
		}
	}
	
	function conf__filtro_alumnos(despacho_ei_formulario $form)
	{
		if(isset($this->s__datos_filtro)){
			$form->set_datos($this->s__datos_filtro);
		}
	}

	function evt__filtro_alumnos__filtrar($datos)
	{
		$this->s__datos_filtro = $datos;
	}

	function evt__filtro_alumnos__cancelar()
	{
		unset($this->s__datos_filtro);
	}



}

?>