------------------------------------------------------------
--[4338]--  DT - cartas_acuerdo 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'despacho', --proyecto
	'4338', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_tabla', --clase
	'15', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'DT - cartas_acuerdo', --nombre
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
	'2018-07-31 16:45:27', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_db_registros
------------------------------------------------------------
INSERT INTO apex_objeto_db_registros (objeto_proyecto, objeto, max_registros, min_registros, punto_montaje, ap, ap_clase, ap_archivo, tabla, tabla_ext, alias, modificar_claves, fuente_datos_proyecto, fuente_datos, permite_actualizacion_automatica, esquema, esquema_ext) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	NULL, --max_registros
	NULL, --min_registros
	'15', --punto_montaje
	'1', --ap
	NULL, --ap_clase
	NULL, --ap_archivo
	'cartas_acuerdo', --tabla
	NULL, --tabla_ext
	NULL, --alias
	'0', --modificar_claves
	'despacho', --fuente_datos_proyecto
	'despacho', --fuente_datos
	'1', --permite_actualizacion_automatica
	NULL, --esquema
	'adscripciones'  --esquema_ext
);

------------------------------------------------------------
-- apex_objeto_db_registros_col
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	'2612', --col_id
	'id_carta_acuerdo', --columna
	'E', --tipo
	'1', --pk
	'cartas_acuerdo_id_carta_acuerdo_seq', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	'cartas_acuerdo'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	'2613', --col_id
	'nro_resol', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	'cartas_acuerdo'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	'2614', --col_id
	'nro_documento_responsable', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'10', --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	'cartas_acuerdo'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	'2615', --col_id
	'objetivo', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'3000', --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	'cartas_acuerdo'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	'2616', --col_id
	'fecha_desde', --columna
	'F', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'cartas_acuerdo'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	'2617', --col_id
	'fecha_hasta', --columna
	'F', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'cartas_acuerdo'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	'2618', --col_id
	'observaciones', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1000', --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'cartas_acuerdo'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	'2619', --col_id
	'esta_firmado', --columna
	'C', --tipo
	'0', --pk
	'', --secuencia
	'1', --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	'cartas_acuerdo'  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'despacho', --objeto_proyecto
	'4338', --objeto
	'2620', --col_id
	'id_tipo_resolucion', --columna
	'E', --tipo
	'0', --pk
	'', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	'cartas_acuerdo'  --tabla
);
--- FIN Grupo de desarrollo 0
