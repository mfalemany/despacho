<?php
	echo '<div class="logo">';
	echo toba_recurso::imagen_proyecto('logo_grande.gif', true);
	echo '</div>';
	toba::consulta_php('co_personas')->get_listado(array('legajo'=>'8218'));
?>