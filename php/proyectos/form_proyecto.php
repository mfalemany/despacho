<?php
class form_proyecto extends despacho_ei_formulario
{
	function extender_objeto_js()
	{
		echo "{$this->objeto_js}.ef('nro_resol').cuando_cambia_valor(function(){
				var resol = {$this->objeto_js}.ef('nro_resol').get_estado();
				{$this->objeto_js}.controlador.ajax('existe_proyecto', resol, this, procesar);
			});
			this.procesar = function(respuesta){
				if(respuesta['encontrado'].length > 0){
					proy = respuesta['encontrado'][0];
					alert('Ya se encuentra cargado el proyecto de TFG del alumno '+proy.alumno);
				}
			};";
		
	}
}

?>