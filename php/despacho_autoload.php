<?php
/**
 * Esta clase fue y ser?generada autom?icamente. NO EDITAR A MANO.
 * @ignore
 */
class despacho_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'despacho_ci' => 'extension_toba/componentes/despacho_ci.php',
		'despacho_cn' => 'extension_toba/componentes/despacho_cn.php',
		'despacho_datos_relacion' => 'extension_toba/componentes/despacho_datos_relacion.php',
		'despacho_datos_tabla' => 'extension_toba/componentes/despacho_datos_tabla.php',
		'despacho_ei_arbol' => 'extension_toba/componentes/despacho_ei_arbol.php',
		'despacho_ei_archivos' => 'extension_toba/componentes/despacho_ei_archivos.php',
		'despacho_ei_calendario' => 'extension_toba/componentes/despacho_ei_calendario.php',
		'despacho_ei_codigo' => 'extension_toba/componentes/despacho_ei_codigo.php',
		'despacho_ei_cuadro' => 'extension_toba/componentes/despacho_ei_cuadro.php',
		'despacho_ei_esquema' => 'extension_toba/componentes/despacho_ei_esquema.php',
		'despacho_ei_filtro' => 'extension_toba/componentes/despacho_ei_filtro.php',
		'despacho_ei_firma' => 'extension_toba/componentes/despacho_ei_firma.php',
		'despacho_ei_formulario' => 'extension_toba/componentes/despacho_ei_formulario.php',
		'despacho_ei_formulario_ml' => 'extension_toba/componentes/despacho_ei_formulario_ml.php',
		'despacho_ei_grafico' => 'extension_toba/componentes/despacho_ei_grafico.php',
		'despacho_ei_mapa' => 'extension_toba/componentes/despacho_ei_mapa.php',
		'despacho_servicio_web' => 'extension_toba/componentes/despacho_servicio_web.php',
		'despacho_comando' => 'extension_toba/despacho_comando.php',
		'despacho_modelo' => 'extension_toba/despacho_modelo.php',
		'dt_guarani' => 'datos/dt_guarani.php',
	);
}
?>