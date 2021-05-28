<?php 
class Dt_guarani{
	
	private $conexion_guarani; //contriene el objeto PDO de conexion_guarani
	private $driver; //string que indica si es un driver PDO u ODBC

	function __construct()
	{
		$this->driver = (strpos(strtolower(php_uname()),'linux') !== false) ? 'pdo' : 'odbc';
		$this->conectar();
		
	}


	//realiza la conexión y devuelve un objeto que depende de sobre que SO corre PHP
	private function conectar(){
	
	//si ya existe la conexión, no se hace nada
		if($this->conexion_guarani){
			return;
		}
		//se obtienen los parametros de conexion_guarani
		$param = json_decode(file_get_contents('../php/actualizacion/guarani.json'),true);
		
		//contiene los parametros de conexion a la base de guarani
		$g = $param['guarani'];
		//contiene los parametros de conexion a la base de postgres
		$p = $param['postgres'];
		
		//si estamos el linux o windows
		if($this->driver == 'pdo'){
			try {
				//CONEXION A GUARANI
				$dsn = $g['driver'].':host='.$g['host'].';service='.$g['service'].';database='.$g['database'].';server='.$g['server'].'; protocol='.$g['protocol'].';EnableScrollableCursors='.$g['EnableScrollableCursors'];
				$this->conexion_guarani = new PDO($dsn,$g['user'],$g['pass']);
			} catch (PDOException $e) {
				var_dump($e->getMessage());
				//se agrega el mensaje de error producido
				//toba::notificacion()->agregar("Ocurri&oacute; el siguente error: ".$e->getMessage());
			}
		}else{
			try {
				$dsn = 'Driver={'.$g['odbc_driver'].'}; Server='.$g['server'].'; Database='.$g['database'].';';
				$this->conexion_guarani = odbc_connect($dsn,$g['user'],$g['pass']);
			} catch (PDOException $e) {
				var_dump($e->getMessage());
				//toba::notificacion()->agregar("Ocurri&oacute; el siguente error: ".$e->getMessage());	
			}
		}
	}

	function consultar_guarani($sql)
	{
		//ejecuta una u otra funcion dependiendo del driver
		if($this->driver == 'odbc'){
			try {
				$resultado = odbc_exec($this->conexion_guarani, $sql);	
				$resultados = array();
				while($registro = odbc_fetch_array($resultado)){
					$resultados[] = $registro;
				}
				return $resultados;
			} catch (PDOException $e) {
				var_dump($e->getMessage());
				//toba::notificacion()->agregar("Ocurri&oacute; el siguente error: ".$e->getMessage());
			}
		}else{
			try {
				if(!$this->conexion_guarani){
					throw new Exception('No se puede conectar a la base de datos de Guarani');
				}
				$this->conexion_guarani->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);	
				$resultado = $this->conexion_guarani->query($sql,2);
				return $resultado->fetchAll();	
			} catch (Exception $e) {
				var_dump($e->getMessage());
				//toba::notificacion()->agregar("Ocurri&oacute; el siguente error: ".$e->getMessage());
			}
		}

	}
/* ===================================================================================================================*/
/* EJECUTAR EN ESTE ORDEN: actualizar_personas(), actualizar_alumnos(), actualizar_docentes(), actualizar_egresados()
/* ===================================================================================================================*/

	function actualizar_personas()
	{
		$personas = $this->consultar_guarani("select per.apellido, 
							per.nombres,
							per.nro_documento, 
							per.nro_inscripcion,
							per.fecha_nacimiento as fecha_nac,
							(select celular_numero 
							from sga_datos_cen_aux as cen
							where cen.celular_numero is not null
							and cen.fecha_relevamiento = (select max(fecha_relevamiento) from sga_datos_cen_aux where nro_inscripcion = cen.nro_inscripcion and celular_numero is not null)
							and cen.nro_inscripcion = per.nro_inscripcion) as telefono,
							(select e_mail
							from sga_datos_censales as cen
							where cen.e_mail is not null
							and cen.fecha_relevamiento = (select max(fecha_relevamiento) from sga_datos_cen_aux where nro_inscripcion = cen.nro_inscripcion and e_mail is not null)
							        and cen.nro_inscripcion = per.nro_inscripcion) as e_mail
							from  sga_personas as per 
							where year(per.fecha_inscripcion) > 2014");

		
		foreach ($personas as $key => $p) {
			$p['fecha_nac'] = ($p['fehca_nac']) ? $p['fehca_nac'] : '1900-01-01';
			$p['apellido'] = str_replace("'","''",$p['apellido']);
			$p['nombres'] = str_replace("'","''",$p['nombres']);
			$sql = "INSERT INTO adscripciones.personas (nro_documento, apellido, nombres, fecha_nac, telefono, e_mail) VALUES ('".$p['nro_documento']."','".$p['apellido']."','".$p['nombres']."','".$p['fecha_nac']."','".$p['telefono']."','".$p['e_mail']."') ON CONFLICT DO NOTHING";
			toba::db()->consultar($sql);
		}
	}

	function actualizar_alumnos()
	{
		$alumnos = $this->consultar_guarani("select per.nro_inscripcion,alu.legajo,per.nro_documento,year(alu.fecha_ingreso) as anio_ingreso,alu.carrera
			from sga_alumnos as alu
			left join sga_personas as per on per.nro_inscripcion = alu.nro_inscripcion
			where year(alu.fecha_ingreso) > 2014
			and alu.carrera in ('01','08')");
		foreach ($alumnos as $key => $a) {
			$sql = "INSERT INTO adscripciones.alumnos (nro_inscripcion, legajo, nro_documento, anio_ingreso, carrera) VALUES ('".$a['nro_inscripcion']."','".$a['legajo']."','".$a['nro_documento']."','".$a['anio_ingreso']."','".$a['carrera']."') ON CONFLICT DO NOTHING";
			toba::db()->consultar($sql);
		}
	}

	function actualizar_docentes()
	{
		$alumnos = $this->consultar_guarani("select legajo, nro_documento
									from sga_docentes as doc
									left join sga_personas as per on per.nro_inscripcion = doc.nro_inscripcion
									where length(per.nro_documento) <= 10");
		foreach ($alumnos as $key => $d) {
			$sql = "INSERT INTO adscripciones.docentes (legajo, nro_documento) VALUES ('".$d['legajo']."','".$d['nro_documento']."') ON CONFLICT DO NOTHING";
			toba::db()->consultar($sql);
		}
	}

	function obtener_egresados()
	{
		$sql = "select legajo from sga_alumnos as alu where calidad = 'E' and alu.plan <> '1963'";
		$egresados = $this->consultar_guarani($sql);
		$legajos = '';
		foreach ($egresados as $key => $value) {
			$legajos .= "'".$value['legajo']."',";
		}
		return substr($legajos,0,strlen($legajos) - 1);
	}


}

$c1 = new Dt_guarani();

//Actualizacion de Datos
$c1->actualizar_personas();
$c1->actualizar_alumnos();
$c1->actualizar_docentes();

//actualizacion de egresados
$egresados = $c1->obtener_egresados();
toba::db()->ejecutar("UPDATE adscripciones.alumnos SET calidad = 'E' WHERE legajo IN ($egresados)");
toba::db()->ejecutar("UPDATE adscripciones.alumnos SET calidad = 'A' WHERE calidad IS NULL");
toba::db()->ejecutar("UPDATE adscripciones.proyecto SET estado = 'E' WHERE alu_legajo IN ($egresados)");

echo "Actualizado con éxito! Puede cerrar esta ventana"; 

?>

