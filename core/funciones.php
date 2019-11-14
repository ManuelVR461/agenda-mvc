<?php

/**
 * Función que llama a la función javascript alerta().
 */
function alerta($mensaje, $tipo='alerta'){
    echo '<script type="text/javascript"></script>';
}

/**
* construye una url, útil para actions de formularios.
*/
function getUrl($action, $controller='',$mainController='index.php'){
	$controller=empty($controller)?$_GET['controller']:$controller ;
	$url.=$mainController=='index.php'?'index.php?':'views/'.$mainController.'.php?';
	$url.='controller='.$controller.'&action='.$action;
	return $url;
}


/**
* indica si hay variable $_POST y no está vacía
*/
function is_post(){
	return ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST) && !empty($_POST));
}

/**
* Indica si una petición -a partir del encabezado del request- es Ajax. Esta se basa en los encabezados seteados por Prototype.
*/
function esAjax(){
	$request = apache_request_headers();
	$request = array_change_key_case($request, CASE_LOWER);
	return (isset($request['x-requested-with']) && $request['x-requested-with'] == 'XMLHttpRequest');
}

/** Función que establece una ruta de acceso a uno o más modelos fuera del contexto de un controlador instanciarlo de la forma ModeloModel::metodo();
 */
function getModel($modelo){
	if(is_array($modelo)){
		foreach($modelo as $model){
			$modelFile = PATH_MODEL . $model.'Model.php';
			if(!file_exists($modelFile)){
				throw new Excepcion("No existe el modelo ".$modelFile);
			}
			require_once($modelFile);
		}
	}else{
		$modelFile = PATH_MODEL . $modelo.'Model.php';
		if(!file_exists($modelFile)){
			throw new Excepcion("No existe el modelo ".$modelFile);
		}
		require_once($modelFile);
	}
}

/**
* Función que redirecciona a 404.php en caso de que haya ingresado una parte de la URL (controlador o vista) incorrecto
*/
function error404($controller='', $action=''){
	$headers = apache_request_headers();
	if(!esAjax()){
		$_ref = '?ref='.urlencode(HREF_APP.getUrl(INDEX_ACTION,INDEX_CONTROLLER));
		if(!headers_sent()){
			exit(header("Location:".HREF_APP."404.php$_ref"));
		}else{
			exit('<script type="text/javascript">window.location="'.HREF_APP.'404.php'.$_ref.'"</script>');
		}
	}else{
		header($_SERVER['SERVER_PROTOCOL']." 404 Not Found", true, 404);
		header('Content-type: application/javascript');
		exit('alerta("Error 404: El recurso solicitado no existe ['.$controller.'-'.$action.']");load(0);if(Modalbox&&Modalbox.initialized){Modalbox.hide();}');
	}
}