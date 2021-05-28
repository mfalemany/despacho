<?php
class form_filtro_proyecto extends despacho_ei_formulario
{
	function extender_objeto_js()
	{

		echo "
		/* Si la opción seleccionada es la de alumnos egresados, se activa el check Incluye Egresados */
		$('#'+{$this->objeto_js}.ef('estado')._id_form).on('change',function(elemento){
			if($('option:selected', $(this) ).text().indexOf('egresad') >= 0){
				$('#'+{$this->objeto_js}.ef('incluye_egresados')._id_form).prop('checked',true);
			}
		});
		";
	}
}

?>