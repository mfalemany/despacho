<?php
class form_ml_tribunal extends despacho_ei_formulario_ml
{
	function extender_objeto_js()
	{
		echo "
			var filas = {$this->objeto_js}.filas()
			for (id_fila in filas) {
				if({$this->objeto_js}.ef('estado').ir_a_fila(id_fila).get_estado() == 'B'){
					{$this->objeto_js}.ef('legajo').ir_a_fila(id_fila).desactivar();
					{$this->objeto_js}.ef('observaciones').ir_a_fila(id_fila).desactivar();
					{$this->objeto_js}.ef('notificado_fecha').ir_a_fila(id_fila).desactivar();
					{$this->objeto_js}.ef('estado').ir_a_fila(id_fila).desactivar();
				}
			}
		";

	}

}
?>