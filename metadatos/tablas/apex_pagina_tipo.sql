
------------------------------------------------------------
-- apex_pagina_tipo
------------------------------------------------------------
INSERT INTO apex_pagina_tipo (proyecto, pagina_tipo, descripcion, clase_nombre, clase_archivo, include_arriba, include_abajo, exclusivo_toba, contexto, punto_montaje) VALUES (
	'despacho', --proyecto
	'login_fca', --pagina_tipo
	'Página de Inicio para sistemas de la FCA', --descripcion
	'login_fca', --clase_nombre
	'tipos_pagina/login_fca.php', --clase_archivo
	NULL, --include_arriba
	NULL, --include_abajo
	NULL, --exclusivo_toba
	NULL, --contexto
	'15'  --punto_montaje
);
