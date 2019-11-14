<?php
/** Clase encargada de gestionar las conexiones a la base de datos. Los datos de acceso son definidos en config_db.php y leídos por Conf.php.
 * @private String $servidor Servidor al cual se accede
 * @private String $usuario nombre de usuario para la conexión
 * @private String $password clave de acceso a la base de datos
 * @private String $base_datos base a la cual se accede
 * @private String $tipo Driver del motor al cual acceder
 * @private Object $link objeto de conexión.
 * @private Array  $data Guarda el resultado de una consulta
 * @private Array $array guarda los datos obetenidos desde una consulta
 * @author Jorge Andrade M.
 */
class mssql{
	private $servidor;
	private $usuario;
	private $password;
	private $base_datos;
	private $tipo;
	private $charset;
	private $link;
	private $data;
	private $array;
	private $last_id;
	private static $_instance;

	/**
	* La función construct es privada para evitar que el objeto pueda ser creado mediante new
	* @author Jorge Andrade M.
	*/
	private function __construct($db){
		$this->setConexion($db);
		$this->conectar();
	}

	/*
	* Método para establecer los parámetros de la conexión.
	* Instancia a la clase Conf.php que es la que lee los datos desde el archivo config_db.php
	* @param String $db Nombre de la base de dato a la cual se conectará
	* @author Jorge Andrade M.
	*/
	private function setConexion($db){
		$conf = Conf::getInstance($db);
		$this->servidor = $conf->getHostDB();
		$this->base_datos = $conf->getDB();
		$this->usuario = $conf->getUserDB();
		$this->password = $conf->getPassDB();
		$this->tipo = $conf->getDBType();
		$this->charset = $conf->getDBCharset();
	}

	/**
	* Evitamos el clonaje del objeto y con ello la multiplicación de conexiones
	* @author Jorge Andrade M.
	*/
	private function __clone(){
		throw new Excepcion("Error: Error de Base de datos, el objeto de conexión no permite más de una instancia");
	}

	/**
	* Restaura la conexión de la instancia original.
	*/
	private function __wakeup(){}

	/**
	* Función encargada de instanciar o crear una instancia de un objeto.
	* En caso de que ya exista una instancia verifica que corresponda con la BD requerida, sino es así destruye y crea una nueva.
	* Esta es la función que debemos llamar desde fuera de la clase para instanciar el objeto, y así, poder utilizar sus métodos.
	* @param String $db Nombre de la base de dato a la cual se conectará
	* @author Jorge Andrade M.
	*/
	public static function getInstance($db){
		if(empty($db) || $db == ''){
			throw new Excepcion("Error: Db::getInstance() debe especificar una base de datos");
		}
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self($db);
		}else if(self::$_instance->base_datos!=$db){
			self::$_instance = new self($db);
		}
		return self::$_instance;
	}

	/**
	* Realiza la conexión a la base de datos.
	* @author Jorge Andrade M.
	*/
	private function conectar(){
		if(!$this->tipo){
			throw new Excepcion("Error: No pudo conectarse al servidor de Base de Datos");
		}
		$this->link = mssql_connect($this->servidor,$this->usuario,$this->password);
		if(!$this->link){
			throw new Excepcion("Error: No pudo conectarse al servidor de Base de Datos ".$this->servidor);
		}
		mssql_select_db($this->base_datos,$this->link);
	}

   /**
    * Método para ejecutar una sentencia sql.
    * @param String $sql texto de la consulta
    * @author Jorge Andrade M.
    */
    public function query($sql){
		$this->data = false;
		if(empty($sql)){
			throw new Excepcion("Error: DB::query() no tiene una consulta definida");
		}
		$query = mssql_query($sql,$this->link);
		if(!$query){
			throw new Excepcion("Error: [MS SQL Error] Hubo un error al procesar su consulta.");
		}
		$this->data = $query;
		return $this->data;
	}

	/**
	* Método para obtener una fila de resultados de la sentencia sql.
	* @param Array $data resultado obtenido desde $this->query.
	* @param Int $fila Número de la fila (orden) de la cual queremos sacar datos
	* @uses : while($row = [instanciaDeDB]->getRow($resultQuery,$fila){
	*           $data[] = $row;
	*         }
	* @author Jorge Andrade M.
	*/
	public function getRow($data){
		if($data){
			$this->array = mssql_fetch_assoc($data);
		}
		if($this->array){
			// Si hay registros, hacemos trim y utf8_encode recursivo a cada valor
			array_walk_recursive($this->array, 'trim_value');
		}
		return $this->array;
	}

	/**
	* Devuelve el número de filas de una consulta.
	* @param String $sql Texto de la consulta.
	* @return Int $num_rows Cantidad de filas resultantes.
	* @author Jorge Andrade M.
	*/
	public function count($sql){
		return mssql_num_rows($this->query($sql));
	}

	/**
	* Destruye el objeto. Libera resultados y cierra conexión.
	* @author Jorge Andrade M.
	*/
	public function __destruct(){
		@mssql_free_result($this->data);
		@mssql_close($this->link);
	}
}?>