<?php 
class dt_guarani{
	
	private $conexion; //contriene el objeto PDO de conexion
	private $driver; //string que indica si es un driver PDO u ODBC

	function __construct()
	{
		$this->driver = (strpos(strtolower(php_uname()),'linux') !== false) ? 'pdo' : 'odbc';
		$this->conectar();
	}


	//realiza la conexiÃ³n y devuelve un objeto que depende de sobre que SO corre PHP
	private function conectar(){
	
	//si ya existe la conexiÃ³n, no se hace nada
		if($this->conexion){
			return;
		}
		//se obtienen los parametros de conexion
		$param = json_decode(file_get_contents('../instalacion/guarani.json'),true);

		//si estamos el linux o windows
		if($this->driver == 'pdo'){
			try {
				$dsn = $param['driver'].':host='.$param['host'].';service='.$param['service'].';database='.$param['database'].';server='.$param['server'].'; protocol='.$param['protocol'].';EnableScrollableCursors='.$param['EnableScrollableCursors'];
				//se realiza la conexion
						
				$this->conexion = new PDO($dsn,$param['user'],$param['pass']);
				

			} catch (PDOException $e) {
				//se agrega el mensaje de error producido
				throw new toba_error($e);
				//toba::notificacion()->agregar("Ocurri&oacute; el siguente error: ".$e->getMessage());
			}
		}else{
			
			try {
				$dsn = 'Driver={'.$param['odbc_driver'].'}; Server='.$param['server'].'; Database='.$param['database'].';';
				$this->conexion = odbc_connect($dsn,$param['user'],$param['pass']);
			} catch (PDOException $e) {
				toba::notificacion()->agregar("Ocurrió el siguente error: ".$e->getMessage());	
			}
		}	
	}


	function consultar($sql)
	{
		//ejecuta una u otra funcion dependiendo del driver
		if($this->driver == 'odbc'){
			try {
				$resultado = odbc_exec($this->conexion, $sql);	
				$resultados = array();
				while($registro = odbc_fetch_array($resultado)){
					$resultados[] = $registro;
				}
				return $resultados;
			} catch (PDOException $e) {
				toba::notificacion()->agregar("Ocurrió el siguente error: ".$e->getMessage());
			}
		}else{
			try {
				$this->conexion->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);	
				$resultado = $this->conexion->query($sql,2);
				return $resultado->fetch();	
			} catch (Exception $e) {
				toba::notificacion()->agregar("Ocurrió el siguente error: ".$e->getMessage());
			}
		}

	}
}
?>
