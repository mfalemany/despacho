<?php
class fuente_despacho extends toba_fuente_datos
{
    /**
    *   Antes de conectarse a la base de datos, le digo que la misma posee esquema de auditoría
        *       De esta forma se creará automáticamente la tabla temporal y se asignará el usuario.
    */
    function pre_conectar()
    {
            $this->set_fuente_parsea_errores(true);
    }

    function parsear_sqlstate_23503($accion, $sql, $mensaje){
    	toba::notificacion()->error("Accion: ".$accion." en SQL: ".$sql." - Mensaje: ".$mensaje);
    }


}
?>