<?php
class ci_seleccion_docente extends despacho_ci
{
	protected $s__filtro;
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
	//---- seleccion_docente ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__seleccion_docente(despacho_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		if(isset($this->s__filtro)){
			return toba::consulta_php('co_docentes')->get_listado_abreviado($this->s__filtro);	
		}else{
			return toba::consulta_php('co_docentes')->get_listado_abreviado();	
		}
		
	}
}

?>