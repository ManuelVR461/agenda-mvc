<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Fecha en el pasado
require_once 'version.php';
require_once 'core/config_app.php';
require_once 'core/path.php';
require_once 'core/funciones.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';

if(DB_DEFAULT==''){
	$html = "<div style='text-align:center;color:#f00;font-weight:bold;''>";
	$html .= "Error: No ha definido una base de datos por defecto en config_app.php y config_db.php";
	$html .= "</div>";
	exit($html);
}

// ######################
// # Control de errores #
// ######################
// if(defined('CONTROL_DE_ERROR') && CONTROL_DE_ERROR == 'S'){
// 	ini_set('display_errors',0);
// 	register_shutdown_function('captura_error');
// }
#################
# ID de versión #
#################
if(!isset($_GET['v']) || empty($_GET['v'])){
	$_GET['v'] = APP_DATE_VERSION.'_'.APP_VERSION;
}