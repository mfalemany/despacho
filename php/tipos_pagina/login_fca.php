<?php
/**
 * Tipo de página pensado para pantallas de login, presenta un logo y un pie de página básico
 * 
 * @package SalidaGrafica
 */
class login_fca extends toba_tp_basico
{
	function barra_superior()
	{
		echo "
			<style type='text/css'>
				.cuerpo {
					
				}
			</style>
		";
		echo "<div id='barra-superior' class='barra-superior-login'>\n";		
	}	

	function pre_contenido()
	{
		echo "<h1 class='login-titulo' style='color:#5959a5; padding: 50px 0px 5px 0px;'>Sistema de Gesti&oacute;n<br>Despacho FCA</h1>";
		//echo "<div class='login-titulo'>". toba_recurso::imagen_proyecto("logo.gif",true);
		//echo "<div>versi&oacute;n ".toba::proyecto()->get_version()."</div>";
		//echo "</div>";
		echo "\n<div align='center' class='cuerpo'>\n";		
	}

	function post_contenido()
	{
		echo "</div>";		
		echo "<div class='login-pie'>";
		echo "<div>Desarrollado para la <strong><a href='http://www.agr.unne.edu.ar/' style='text-decoration: none' target='_blank'>Facultad de Ciencias Agrarias</a></strong></div>";
		echo "</div>";
	}
}
?>