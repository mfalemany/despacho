------------------------------------------------------------
--[4336]--  ci_cartas_acuerdo - datos 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'despacho', --proyecto
	'4336', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_relacion', --clase
	'15', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'ci_cartas_acuerdo - datos', --nombre
	NULL, --titulo
	NULL, --colapsable
	NULL, --descripcion
	'despacho', --fuente_datos_proyecto
	'despacho', --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	'2018-07-31 16:44:24', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_datos_rel
------------------------------------------------------------
INSERT INTO apex_objeto_datos_rel (proyecto, objeto, debug, clave, ap, punto_montaje, ap_clase, ap_archivo, sinc_susp_constraints, sinc_orden_automatico, sinc_lock_optimista) VALUES (
	'despacho', --proyecto
	'4336', --objeto
	'0', --debug
	NULL, --clave
	'2', --ap
	'15', --punto_montaje
	NULL, --ap_clase
	NULL, --ap_archivo
	'1', --sinc_susp_constraints
	'1', --sinc_orden_automatico
	'1'  --sinc_lock_optimista
);

------------------------------------------------------------
-- apex_objeto_dependencias
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'despacho', --proyecto
	'3040', --dep_id
	'4336', --objeto_consumidor
	'4338', --objeto_proveedor
	'cartas_acuerdo', --identificador
	'', --parametros_a
	'1', --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'2'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'despacho', --proyecto
	'3039', --dep_id
	'4336', --objeto_consumidor
	'4337', --objeto_proveedor
	'partes_acuerdo', --identificador
	'2', --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'3'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'despacho', --proyecto
	'3041', --dep_id
	'4336', --objeto_consumidor
	'2510', --objeto_proveedor
	'resoluciones', --identificador
	NULL, --parametros_a
	'1', --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'1'  --orden
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_datos_rel_asoc
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'despacho', --proyecto
	'4336', --objeto
	'163', --asoc_id
	NULL, --identificador
	'despacho', --padre_proyecto
	'4338', --padre_objeto
	'cartas_acuerdo', --padre_id
	NULL, --padre_clave
	'despacho', --hijo_proyecto
	'4337', --hijo_objeto
	'partes_acuerdo', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'2'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'despacho', --proyecto
	'4336', --objeto
	'164', --asoc_id
	NULL, --identificador
	'despacho', --padre_proyecto
	'2510', --padre_objeto
	'resoluciones', --padre_id
	NULL, --padre_clave
	'despacho', --hijo_proyecto
	'4338', --hijo_objeto
	'cartas_acuerdo', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'1'  --orden
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_rel_columnas_asoc
------------------------------------------------------------
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'despacho', --proyecto
	'4336', --objeto
	'163', --asoc_id
	'4338', --padre_objeto
	'2612', --padre_clave
	'4337', --hijo_objeto
	'2177'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'despacho', --proyecto
	'4336', --objeto
	'164', --asoc_id
	'2510', --padre_objeto
	'2630', --padre_clave
	'4338', --hijo_objeto
	'2613'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'despacho', --proyecto
	'4336', --objeto
	'164', --asoc_id
	'2510', --padre_objeto
	'2631', --padre_clave
	'4338', --hijo_objeto
	'2620'  --hijo_clave
);
