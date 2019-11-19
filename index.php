<?php
require_once 'start.php';

if(!empty($_SESSION['data']['pase'])){
	session_start();
}

require_once 'core/session.php';
require_once 'templates/default.php';