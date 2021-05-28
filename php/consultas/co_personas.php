<?php
class co_personas
{
	/*si el parametro 'reducido' se establece a true, la consulta retorna 
	/ el apellido y el nombre de la persona concatenados en un solo campo
	*/
	function get_listado($filtro=array(),$reducido = false)
	{
		$where = array();
		if (isset($filtro['nro_documento'])) {
			$where[] = "nro_documento ILIKE ".quote("%{$filtro['nro_documento']}%");
		}
		if (isset($filtro['apellido'])) {
			$where[] = "t_p.apellido ILIKE ".quote("%{$filtro['apellido']}%");
		}
		if (isset($filtro['nombres'])) {
			$where[] = "t_p.nombres ILIKE ".quote("%{$filtro['nombres']}%");
		}
		
		$sql = "SELECT t_p.nro_documento, ";
		if($reducido){
			$sql .= "t_p.apellido||', '||t_p.nombres as persona, ";
		}else{
			$sql .= "t_p.apellido, t_p.nombres, ";
		}	
		$sql .= "t_p.fecha_nac,
				t_p.telefono,
				t_p.e_mail
			FROM
				personas as t_p
			ORDER BY apellido";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		$resultado = toba::db()->consultar($sql);
		//ei_arbol($this->buscar_en_ws($filtro['nro_documento'])); return;
		//Si se encuentra se retorna, y si no se encuentra nada, se intenta en el WS
		if(count($resultado) == 0){ 
			$encontrados = array();
			//$encontrados = $this->buscar_en_guarani($filtro);
			if($encontrados){
				return $encontrados;
			}else{
				$persona = $this->buscar_en_ws($filtro['nro_documento']);
				if(count($persona)){
					$this->guardar_en_local($persona);
					return $persona;
				}else{
					return array();
				}
			}
		}else{
			return $resultado;
		}
	}

	function buscar_en_guarani($filtro = array())
	{
		if(!count($filtro)){
			return FALSE;
		}
		$resultado = toba::consulta_php('co_guarani')->buscar_personas($filtro);
		if($resultado){
			if(array_key_exists('nro_documento',$resultado)){
				$resultado = array(0=>$resultado);
			}
			foreach ($resultado as $registro){
				$persona = array(
					'nro_documento'   => $registro['nro_documento'],
					'apellido'        => $registro['apellido'],
					'nombres'         => $registro['nombres'],
					'fecha_nac'       => $registro['fecha_nacimiento'],
					'telefono'        => $registro['celular_numero'],
					'e_mail'          => $registro['e_mail'],
					'nro_inscripcion' => $registro['nro_inscripcion'],
					'legajo'          => $registro['legajo'],
					'anio_ingreso'    => $registro['anio_ingreso'],
					'carrera'         => $registro['carrera'],
					'calidad'         => $registro['calidad']
				) ;
				
				$sql_persona = "INSERT INTO personas (nro_documento,apellido,nombres,fecha_nac,telefono,e_mail) VALUES (
					".quote($persona['nro_documento']).",
					".quote($persona['apellido']).",
					".quote($persona['nombres']).",
					".quote($persona['fecha_nac']).",
					".quote($persona['telefono']).",
					".quote($persona['e_mail']).") ON CONFLICT DO NOTHING;";
				$sql_alumno = "INSERT INTO alumnos (nro_inscripcion, legajo, nro_documento, anio_ingreso, carrera, calidad) VALUES (".quote($persona['nro_inscripcion']).",
							".quote($persona['legajo']).",
							".quote($persona['nro_documento']).",
							".quote($persona['anio_ingreso']).",
							".quote($persona['carrera']).",
							".quote($persona['calidad']).")  ON CONFLICT DO NOTHING;";
				
				try {
					toba::db()->ejecutar($sql_persona);	
					toba::db()->ejecutar($sql_alumno);	
				} catch (toba_error_db $e) {
					toba::notificacion()->agregar($e->get_mensaje());
				}
				catch (Exception $e) {
					toba::notificacion()->agregar($e->getMessage());
				}
				
			}
			return $resultado;
		}else{
			return FALSE;
		}
	}


	static function get_ayn($nro_documento){
		$sql = "SELECT apellido||', '||nombres as ayn from personas where nro_documento = '$nro_documento' limit 1";
		$resultado = toba::db()->consultar_fila($sql);
		if(isset($resultado['ayn'])){
			return $resultado['ayn'];	
		}else{
			$persona = $this->buscar_en_ws($nro_documento);
			if(count($persona)){
				$consulta->guardar_en_local($persona);
				unset($consulta);
				$resultado = toba::db()->consultar_fila('SELECT apellido||", "||nombres as ayn FROM personas WHERE nro_documento = '.quote($nro_documento));
				if(count($resultado)){
					return $resultado['ayn'];
				}
			}else{
				return '';
			}
		}
	}

	//funcion que recibe un patron de busqueda, y retorna apellido y nombre coincidentes
	function get_ayn_patron($patron)
	{
		$sql = "SELECT nro_documento, apellido||', '||nombres AS ayn FROM personas WHERE apellido||' '||nombres ILIKE ".quote('%'.$patron.'%');
		return toba::db()->consultar($sql);
	}

	function generar_dni_random()
	{
		return time();
	}

	protected function guardar_en_local($persona)
	{
		extract($persona);
		$sql = "INSERT INTO personas (nro_documento,apellido,nombres,fecha_nac,e_mail) 
		        VALUES ('$nro_documento','".ucwords(strtolower($apellido))."','".ucwords(strtolower($nombres))."','$fecha_nac','$mail')";
		$afectados = toba::db()->ejecutar($sql);
		if($afectados){
			if(isset($persona['ua']) && $persona['ua'] == 'AGR'){
				$sql = "INSERT INTO alumnos VALUES ('".$persona['nro_insc']."','No definido','".$persona['nro_documento']."','0','','".$persona['calidad']."');";
				toba::db()->consultar($sql);
			}

		}

	}

	function buscar_en_ws($nro_documento)
	{
		$cliente = toba::servicio_web_rest('ws_unne')->guzzle();
		$url = "agentes/".str_replace(".","",$nro_documento)."/datoscomedor";
		$response = $cliente->get($url);
		var_dump($response); die;
		$datos = rest_decode($response->json());
		//ei_arbol($datos)a; die;
		$persona = array();
		if(array_key_exists('MAPUCHE',$datos)){
			//obtengo los datos de mapuche (si existen)
			$tmp = $datos['MAPUCHE'][0];
			//separo el apellido y el nombre
			$ayn = explode(',',$tmp['ayn']);
			//asigno todos los valores
			$persona['nro_documento'] = $nro_documento;
			$persona['apellido'] = $ayn[0];
			$persona['nombres']  = $ayn[1];
			$persona['mail'] = NULL; 
			$persona['cuil'] =  $tmp['cuit'];
			$persona['sexo'] = NULL;
			$persona['fecha_nac'] = '1900-01-01';
		}
		if(array_key_exists('GUARANI',$datos)){
			//obtengo los datos de guarani (si existen)
			$tmp = $datos['GUARANI'][0];
					
			//asigno todos los valores
			$persona['carrera'] = $tmp['CARRERA'];
			$persona['calidad'] = $tmp['CALIDAD'];
			$persona['nro_insc'] = $tmp['NRO_INSC'];
			$persona['ua'] = $tmp['UA'];
			$persona['nro_documento'] = $nro_documento;
			$persona['apellido'] = $tmp['APELLIDO'];
			$persona['nombres']  = $tmp['NOMBRES'];
			$persona['mail'] = $tmp['EMAIL'];
			$persona['cuil'] = (isset($persona['cuil']) && $persona['cuil']) ? $persona['cuil'] : 'XX-'.$nro_documento.'-X';
			$persona['sexo'] = $tmp['SEXO'];
			$persona['fecha_nac'] = '1900-01-01';
		}


		if(isset($persona['apellido'])){
			$persona['apellido'] = mb_convert_encoding($persona['apellido'], "LATIN1", "auto");
			$persona['nombres'] = mb_convert_encoding($persona['nombres'], "LATIN1", "auto");

			$persona['apellido'] = ucwords(strtolower($persona['apellido'])) ;
			$persona['nombres'] = ucwords(strtolower($persona['nombres'])) ;
		}
		return $persona;	
	}

	function existe_persona($nro_documento)
	{
		if(!$nro_documento){
			return false;
		}
		if($this->existe_en_local($nro_documento)){
			$this->actualizar_datos($nro_documento);
			return true;
		}else{
			$datos = $this->buscar_en_ws($nro_documento);
			if(count($datos)){
				$persona = array('nro_documento' => $datos['nro_documento'],
								 'apellido'      => $datos['apellido'],
								 'nombres'       => $datos['nombres'],
								 'fecha_nac'     => $datos['fecha_nac'],
								 'e_mail'        => $datos['mail']);
				return $this->nueva_persona($persona);
			}else{
				return false;
			}
		}
	}

	function existe_en_local($nro_documento)
	{
		return is_array(toba::db()->consultar_fila('SELECT * FROM personas WHERE nro_documento = '.quote($nro_documento)));
	}

	function actualizar_datos($nro_documento)
	{
		//Si no se recibe un DNI no se hace nada
		if(!$nro_documento){
			return;
		}
		//Busco los datos en fuentes externas
		$datos = $this->buscar_en_ws($nro_documento);

		//si encuentro algo, intento actualizar
		if(count($datos)){
			$persona = array('apellido'  => $datos['apellido'],
							 'nombres'   => $datos['nombres'],
							 'fecha_nac' => $datos['fecha_nac'],
							 'e_mail'    => $datos['mail']);
			//Esta variable se modifica a TRUE solamente si se encuentra algun campo no vac?
			$actualizar = FALSE;
			//Consulta de actualizacion
			$sql = "UPDATE personas SET ";
			
			//no se actualizan el apellido y el/los nombres			
			if(isset($persona['apellido'])){
				unset($persona['apellido']);
			}
			if(isset($persona['nombres'])){
				unset($persona['nombres']);
			}

			//Recorro todos los campos obtenidos del WS
			foreach($persona as $campo => $valor){
				//Si es no nulo, lo agrego a la consulta
				if($valor){
					if(preg_match('/fecha/',$campo)){
						$tmp = new Datetime(str_replace('/','-',$valor));
						$valor = $tmp->format('Y-m-d');

					}
					$actualizar = TRUE;
					$sql .= $campo." = ".quote($valor).",";
				}
			}
			//Si se agreg?alg?n campo a la consulta, se ejecuta
			if($actualizar){
				//quito la ?ltima coma agregada entre los campos
				$sql = substr($sql,0,strlen($sql)-1);
				//Condidici? de actualizaci?
				$sql .= " WHERE nro_documento = ".quote($nro_documento);
				
				toba::db()->ejecutar($sql);
			}
		}
	}

	function buscar_persona($dni)
	{
		$datos = $this->get_personas(array('nro_documento'=>$dni));
		$datos = (count($datos) > 0) ? array_shift($datos) : NULL;
		
		//si se cumple este if es porque la persona no existe en local
		if( ! $datos){
			$datos = $this->buscar_en_ws($dni);
			if(count($datos)){
				$this->nueva_persona($datos);
			}
		}
		return $datos;
	}
	function buscar_persona_combo_editable($patron)
	{
		$sql = "SELECT nro_documento, ' ('||nro_documento||') '||per.apellido||', '||per.nombres AS ayn 
				FROM adscripciones.personas AS per 
				WHERE per.nro_documento||' '||per.apellido||' '||per.nombres ILIKE".quote('%'.$patron.'%');
		return toba::db()->consultar($sql);
	}
	function nueva_persona($datos)
	{
		if( ! array_key_exists('nro_documento',$datos) || ! $datos['nro_documento']){
			return false;
		}
		$sql = $this->armar_insert($datos,'personas');
		return toba::db()->ejecutar($sql);
	}
	function asegurar_existencia_usuario($datos_usuario)
	{   
		if( ! $this->get_personas(array('nro_documento' => $datos_usuario['id'])) ){
			$this->nueva_persona(array(
									   'nro_documento' => $datos_usuario['id'],
									   'e_mail'		  => $datos_usuario['email']
									   )
								);
		}

	}

	function armar_insert($datos = array(),$tabla="")
	{
		if(count($datos) == 0 || $tabla == ''){
			return FALSE;
		}
		$campos = "";
		$valores = "";
		foreach ($datos as $campo => $valor) {
			if($valor){
				//si es un campo tipo fecha, lo formateo
				if(preg_match('/fecha/',$campo)){
					$tmp = new Datetime(str_replace('/','-',$valor));
					$valor = $tmp->format('Y-m-d');
				}
				$campos .= $campo.",";
				$valores .= quote($valor).",";	
			}
		}
		$campos = substr($campos,0,$campos-1);
		$valores = substr($valores,0,$valores-1);
		return "INSERT INTO $tabla ($campos) VALUES ($valores)";

	}

	
}

?>
