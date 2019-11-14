<?php
class Model{

	public static $msgnumber;
	public static $severity;
	public static $state;
	public static $line;
	public static $text;
	public static $Db;
	private static $_host;

	public function  __construct(){
		self::$msgnumber = 0;
		self::$severity = 0;
		self::$state = 0;
		self::$line = 0;
		self::$text = '';
		self::$Db = '';
	}

	/** Ejecuta una consulta y devuelve el resultado.
	*
	* @param String $sql		Texto de la consulta.
	* @param String $dataBase	Nombre de la Base de datos a la cual se conectará, por defecto es la indicada en
	*							config_app.php en la constante DB_DEFAULT
	* @return Array $result
	*
	*/
	public static function query($sql, $dataBase = DB_DEFAULT){
		self::_getDriver($dataBase);
		$db = call_user_func(self::$Db . '::getInstance',$dataBase);
		$ti = microtime(true);
		$r = $db->query($sql);
		$tf = microtime(true);
		self::_log($ti,$tf,$sql,$r);
		return $r;
	}

	/**
	* Devuelve una array con el PRIMER resultado obtenido de una consulta
	*
	* @param String $sql texto de la consulta.
	* @param String $dataBase	Nombre de la Base de datos a la cual se conectará, por defecto es la indicada en
	*							config_app.php en la constante DB_DEFAULT
	*
	* @return Array $data Array con los datos de la consulta realizada.
	*
	*/
	public static function select($sql, $dataBase=DB_DEFAULT){
		self::_getDriver($dataBase);
		$db = call_user_func(self::$Db . '::getInstance',$dataBase);
		$ti = microtime(true);
		$data = $db->getRow($db->query($sql));
		$tf = microtime(true);
		self::_log($ti,$tf,$sql,$data);
		return $data;
	}

	/**
	* Devuelve una array con TODOS los datos obtenidos de una consulta
	*
	* @param String $sql texto de la consulta.
	* @param String $dataBase	Nombre de la Base de datos a la cual se conectará, por defecto es la indicada en
	*							config_app.php en la constante DB_DEFAULT
	* @return Array $data Array con los datos de la consulta realizada.
	*
	*/
	public static function selectAll($sql, $dataBase=DB_DEFAULT){
		self::_getDriver($dataBase);
		$db = call_user_func(self::$Db . '::getInstance',$dataBase);
		$ti = microtime(true);
		$query = $db->query($sql);
		$data = '';
		while($row=$db->getRow($query)){
			$data[]=$row;
		}
		$tf = microtime(true);
		self::_log($ti,$tf,$sql,$data);
		return $data;
	}

	/**
	* require_once al driver correspondiente
	* @param String $dataBase	Nombre de la Base de datos a la cual se conectará.
	*/
	private static function _getDriver($dataBase){
		require_once dirname(dirname(__FILE__)). '/core/Conf.php';
		$conf = Conf::getInstance($dataBase);
		if(!file_exists(dirname(dirname(__FILE__)). '/core/Db.'.$conf->getDBType().'.php')){
			throw new Excepcion("Error de Base de datos. No existe el driver especificado.");
		}
		require_once dirname(dirname(__FILE__)). '/core/Db.'.$conf->getDBType().'.php';
		self::$Db = $conf->getDBType();
		self::$_host = $conf->getHostDB();
	}

	/**
	* Graba log con datos de tiempo de ejecución de consulta
	* @param string	$consulta	sentencia SQL ejecutada
	* @param arry	$resultado	resultado de la sentencia ejecutada
	* @param int	$ti			tiempo en microsegundos antes de ejecutar la consulta
	* @param int	$tf			tiempo en microsegundos después de ejecutar la consulta
	*/
	private static function _log($ti=0,$tf=0,$consulta='',$resultado=null){
		$sep = '||';
		$str = self::$_host.$sep.round($tf-$ti,2).$sep.$consulta.$sep.json_encode($resultado);
		dbg($str,'debug_db_'.date('Ymd').'.log',DEBUG_DB);
	}

}