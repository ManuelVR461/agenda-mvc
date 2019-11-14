<?php
/**
* Control de Session
* Verifica que las sesiones sean válidas.
*/
if (!isset($_SESSION)) {
  session_start();
}
if(empty($_SESSION['data']) || empty($_SESSION['data']['pase']))){
	if(!esAjax()){
		exit(header("Location:login.php?"));
	}else{
		session_destroy();
		header('Content-type: application/javascript');
		die('alert("La sesi&oacute;n de conexi&oacute;n ha caducado. La aplicaci&oacute;n se cerrar&aacute; autom&aacute;ticamente")');
	}
}

?>