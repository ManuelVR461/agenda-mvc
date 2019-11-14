<?php
#################################################
# Rutas a los distintos elementos del Framework #
#################################################
//Directorio ra�z del webserver
define('PATH_ROOT',dirname(dirname(__FILE__)));
//Host de la aplicaci�n
define('HOST_APP',$_SERVER['HTTP_HOST']);
//Directorio de la aplicaci�n sin la ruta completa
define('DIR_APP',"/".basename(PATH_ROOT)."/");
//URL para la ra�z de la aplicaci�n
$HREF_APP = 'http://'.HOST_APP.DIR_APP; //valor por defecto
if(isset($HOST_NAME) && $HOST_NAME != '' && $HOST_NAME == $_SERVER['SERVER_NAME']){
	$HREF_APP = 'http://'.HOST_APP.'/';
}
define('HREF_APP',$HREF_APP);
define('HREF_APP_MACHINE','http://'.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].DIR_APP);
//Directorio a los archivos base de la aplicaci�n
define('PATH_CORE',PATH_ROOT.'/core/');
//Directorio temporal con permisos para escritura (apto para logs)
define('PATH_TMP', LOG_FOLDER);
//Ruta a la clase Controller.php
define('PATH_CLASS_CONTROLLER',PATH_CORE.'Controller.php');
//Ruta a la clase Model.php
define('PATH_CLASS_MODEL',PATH_CORE.'Model.php');
//Ruta al MainController
define('PATH_MAIN',PATH_CORE.'MainController.php');
//Directorio en donde se almacenan las vistas
define('PATH_VIEW',PATH_ROOT.'/views/');
//Directorio en donde se almacenan los controladores
define('PATH_CONTROLLER',PATH_ROOT.'/controllers/');
//Directorio en donde se almacenan los modelos
define('PATH_MODEL',PATH_ROOT.'/models/');
//Directorio en donde se almacenan los helpers
define('PATH_HELPER',PATH_ROOT.'/helpers/');
//Directorio en donde se almacenan los plugin de terceros
define('PATH_VENDOR',PATH_ROOT.'/vendors/');
//Directorio en donde se almacenan los wsdl
define('PATH_WSDL',PATH_ROOT.'/wsdl/');