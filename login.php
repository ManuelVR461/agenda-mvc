<?php

require_once 'start.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo APP_NAME;?></title>
</head>
<body>
    <br>
    <form action="<?php echo URL_LOGIN; ?>" method="POST" id="frmLogin" name="frmLogin">
        usuario: <input type="text" name="usuario"><br><br>
        contraseña <input type="text" name="pwd"><br><br>
        <input type="submit" value="entrar" name="entrar">
    </form>
</body>
</html>