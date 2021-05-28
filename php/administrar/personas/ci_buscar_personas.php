<?php
class ci_buscar_personas extends despacho_ci
{
	protected $s__datos_filtro;

	function conf__filtro_personas(despacho_ei_formulario $form){
		if(isset($this->s__datos_filtro)){
			$form->set_datos($this->s__datos_filtro);
		}
	}
	function conf__cuadro_personas(despacho_ei_cuadro $cuadro)
	{

		$cuadro->desactivar_modo_clave_segura();
		if (isset ($this->s__datos_filtro))	{
			$cuadro->set_datos(toba::consulta_php('co_personas')->get_listado($this->s__datos_filtro,true));
		}else{
			$cuadro->set_datos(toba::consulta_php('co_personas')->get_listado(array(),true));
		}
	}

	function evt__filtro_personas__filtrar($datos){
		$this->s__datos_filtro = $datos;
	}

	function evt__filtro_personas__cancelar(){
		unset($this->s__datos_filtro);
	}

	



	

}

?>