<?php

class LoginController extends Controller {

    public function login() {
        if (is_post()) {
            dbg(json_encode($_POST), 'login_log' . date('Ymd') . '.log');
            if($_POST['usuario']="Manuel" && $_POST['pwd']="1234"){
                $_SESSION['data']['pase']="SI";
                $_SESSION['data']['nombres']="Manuel Ramirez";
                $_SESSION['data']['cargo']="Desarrolador";
            }else{
                $_SESSION['data']['pase']="NO";
            }
        }
    }

    public function logout() {
        if (ini_get("session.use_cookies")) {
            $prm = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $prm['path'], $prm['domain'], $prm['secure'], $prm['httponly']);
        }
        $_SESSION = null;
        session_destroy();
        $this->redirect('login');
    }
}