<?php

class Controller {

    protected $models = array();
    protected $data = array();
    public $helpers = array();
    public $controller = null;
    public $action = null;


    /** Constructor de la Clase, llama al o los modelos respectivo
     * y helpers en caso de que esten definidos
     */
    public function __construct() {
        $this->action = $_GET['action'];
        $this->controller = $_GET['controller'];
        $this->getModel();
        $this->getHelper();
    }

    /** Función que devuelve el archivo de modelo relacionado a un Controlador
     *  permitiendo instanciarlo de la forma ClaseModelo::metodo();
     */
    protected function getModel() {
        $modelName = str_replace('Controller', '', get_class($this));
        if (count($this->models) == 0) {
            $modelFile = $modelName . 'Model.php';
            if (!file_exists(PATH_MODEL . $modelFile)) {
                throw new Excepcion('Error: El Controlador ' . $modelName . ' no tiene un Modelo Asociado: ' . $modelFile . ' no existe');
            }
            require_once(PATH_MODEL . $modelFile);
        } else {
            $this->models[] = $modelName;
            foreach (array_unique($this->models) as $model) {
                $modelFile = $model . 'Model.php';
                if (!file_exists(PATH_MODEL . $modelFile)) {
                    throw new Excepcion('Error: El Controlador ' . $model . ' no tiene un Modelo Asociado: ' . $modelFile . ' no existe');
                }
                require_once(PATH_MODEL . $modelFile);
            }
        }
    }


    /**
    * Devuelve la vista solicitada según los parámetros de URL.
    * Se puede forzar el renderizado de otra vista si se pasa como parámetro de entrada del método.
    */
    protected function getView($action = '', $controller = '') {
        $action = (!empty($action) ? $action : strtolower($_GET['action'])) . '.php';
        $controller = !empty($controller) ? $controller : strtolower($_GET['controller'] . "/");
        if (file_exists(PATH_VIEW . $controller . $action)) {
            //buscamos si hay datos definidos para pasar a la vista
            if (!empty($this->data)) {
                foreach ($this->data as $key => $value) {
                    $$key = $value;
                }
            }
            return require_once(PATH_VIEW . $controller . $action);
        } else {
            error404($controller, $action);
        }
    }


    /** Función que instancia los helper indicados en $this->helper
    *  Se acceden desde la vista con la forma $Class->metodo();
    *
    */
    protected function getHelper() {
        if (count($this->helpers) > 0) {
            foreach ($this->helpers as $valueHelper) {
                getFile('helpers', $valueHelper . '.php');
            }
        }
    }

    /** Define una variable dentro $data si ya existe la sobreescribe.
    * Estas variables son accesibles en la vista.
    * @param String $name nombre de la variable
    * @param mixed $value valor de la variable
    */
    protected function set($name, $value) {
        $this->data[$name] = $value;
    }

    /** Redirecciona a una vista de acuerdo al controlador y acción indicados, por defecto
     * va al controlador desde donde se llama y al action index en el archivo index.php
     * @param String	$action				Acción del controlador que llamará a la vista respectiva
     * @param String	$controller 		Nombre del controlador al cual redireccionar
     * @param Array	$params				Parámetros que serán pasados al controlados via $_GET
     * @param String	$frontController	Nombre del archivo que instancia a la clase MainController()
     *
     */
    protected function redirect($action = '', $controller = '', $params = array(), $frontController = 'index.php') {
        $params = !empty($params) ? '&' . http_build_query($params) : '';
        if (strpos($action, 'http') === false) {
            if (empty($action) || $action == null) {
                $action = 'index';
            }
            if (empty($controller)) {
                $controller = strtolower(str_replace("Controller", "", get_class($this)));
            }
            $url = $frontController . '?controller=' . $controller . '&action=' . $action . $params;
        } else {
            $url = $action . '?' . $params;
        }
        if (!headers_sent()) {
            exit(header("Location: " . $url));
        } else {
            exit('<script type="text/javascript">window.location="' . $url . '"</script>');
        }
    }

    /** Igual a redirect() pero acá envía los datos mediante POST
     * @param String	$action				Acción del controlador que llamará a la vista respectiva
     * @param String	$controller 		Nombre del controlador al cual redireccionar
     * @param Array	$params				Parámetros que serán pasados al controlados via $_GET
     * @param String	$frontController	Nombre del archivo que instancia a la clase MainController()
     *
     */
    protected function redirectPost($action = '', $controller = '', $params = array(), $frontController = "index.php") {
        if (strpos($action, 'http') === false) {
            if (empty($action) || $action == null) {
                $action = 'index';
            }
            if (empty($controller)) {
                $controller = strtolower(str_replace("Controller", "", get_class($this)));
            }
            $url = $frontController . '?controller=' . $controller . '&action=' . $action;
        } else {
            $url = $action;
        }
        $form = '<form action="' . $url . '" method="post" id="_redirecPostPhp">';
        foreach ($params as $key => $value) {
            $form .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
        }
        $form .= '</form><script type="text/javascript">document.getElementById("_redirecPostPhp").submit()</script>';
        exit($form);
    }

}