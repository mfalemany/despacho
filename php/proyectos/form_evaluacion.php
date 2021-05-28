<?php
class form_evaluacion extends despacho_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		//obtengo el ID del proyecto que se está editando
		$proyecto = $this->controlador()->controlador()->dep('datos')->tabla('proyecto')->get();
		$id_proyecto = $proyecto['id_proyecto'];
		
		
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__legajo__procesar = function(es_inicial)
		{
			var params = {id_proyecto: ".$id_proyecto.", legajo: {$this->objeto_js}.ef('legajo').get_estado()};
			{$this->controlador()->objeto_js}.ajax('get_posibles_resultados_por_evaluador',params, this, cargar_opciones);
		}
		
		function cargar_opciones(respuesta)
		{
			console.log(respuesta);
			{$this->objeto_js}.ef('resultado').set_opciones(respuesta);
		}
		";
		//
	}


}
?>