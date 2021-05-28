
------------------------------------------------------------
-- apex_fuente_datos
------------------------------------------------------------
INSERT INTO apex_fuente_datos (proyecto, fuente_datos, descripcion, descripcion_corta, fuente_datos_motor, host, punto_montaje, subclase_archivo, subclase_nombre, orden, schema, instancia_id, administrador, link_instancia, tiene_auditoria, parsea_errores, permisos_por_tabla, usuario, clave, base) VALUES (
	'despacho', --proyecto
	'despacho', --fuente_datos
	'Fuente despacho', --descripcion
	'despacho', --descripcion_corta
	'postgres7', --fuente_datos_motor
	NULL, --host
	'15', --punto_montaje
	'datos/fuentes/despacho.php', --subclase_archivo
	'fuente_despacho', --subclase_nombre
	NULL, --orden
	'adscripciones', --schema
	'despacho', --instancia_id
	NULL, --administrador
	'1', --link_instancia
	'0', --tiene_auditoria
	'1', --parsea_errores
	'0', --permisos_por_tabla
	NULL, --usuario
	NULL, --clave
	NULL  --base
);
