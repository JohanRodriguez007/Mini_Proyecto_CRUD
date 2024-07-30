<?php

//-----------------------------------------------------
// Variables
//-----------------------------------------------------
$email = isset($_REQUEST['email']) ? urldecode($_REQUEST['email']) : '';
$token = isset($_REQUEST['token']) ? urldecode($_REQUEST['token']) : '';

//-----------------------------------------------------
// COMPROBAR SI SON CORRECTOS LOS DATOS
//-----------------------------------------------------
// Conecta con base de datos
$miPDO = new PDO('sqlite:base-de-datos.sqlite');
// Prepara SELECT para obtener la contraseña almacenada del usuario
$miConsulta = $miPDO->prepare('SELECT COUNT(*) as length FROM usuarios WHERE email = :email AND token = :token AND activo = 0;');
// Ejecuta consulta
$miConsulta->execute([
    'email' => $email,
    'token' => $token
]);
$resultado = $miConsulta->fetch();
// Existe el usuario con el token
if ((bool) $resultado['length']) {
    //-----------------------------------------------------
    // ACTIVAR CUENTA
    //-----------------------------------------------------
    // Prepara la actualización
    $miActualiacion = $miPDO->prepare('UPDATE usuarios SET activo = 1 WHERE email = :email;');
    // Ejecuta actualización
    $miActualiacion->execute([
        'email' => $email
    ]);
    //-----------------------------------------------------
    // REDIRECCIONAR A IDENTIFICACION
    //-----------------------------------------------------
    header('Location: identificarse.php?activada=1');
    die();
}

// No es un usuario válido, le enviamos al formulario de identificación
header('Location: identificarse.php');
die();
