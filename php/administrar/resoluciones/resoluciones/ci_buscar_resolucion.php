<?php
class ci_buscar_resolucion extends despacho_ci
{
	protected $s__filtro;
	//-----------------------------------------------------------------------------------
	//---- buscar_resol -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__buscar_resol(despacho_ei_cuadro $cuadro)
	{
		$cuadro->desactivar_modo_clave_segura();
		if(isset($this->s__filtro)){
			return toba::consulta_php('co_resoluciones')->get_listado($this->s__filtro);
		}else{
			return toba::consulta_php('co_resoluciones')->get_listado();
		}
	}

	//-----------------------------------------------------------------------------------
	//---- filtro_resol -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro_resol(despacho_ei_formulario $form)
	{
		if(isset($this->s__filtro)){
			$form->set_datos($this->s__filtro);
		}
	}

	function evt__filtro_resol__filtrar($datos)
	{
		$this->s__filtro = $datos;
	}

	function evt__filtro_resol__cancelar()
	{
		unset($this->s__filtro);
	}

}
?>