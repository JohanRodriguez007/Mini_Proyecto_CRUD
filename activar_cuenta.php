<?php
// Configuración de la base de datos
$BD = 'mini_proyecto';
$miPDO = new PDO("mysql:host=127.0.0.1; dbname=$BD;", 'root', '');

// Obtener parámetros de la URL
$email = isset($_GET['email']) ? $_GET['email'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Verificar que ambos parámetros están presentes
if (empty($email) || empty($token)) {
    die('Parámetros inválidos');
}

// Decodificar el email y el token
$email = urldecode($email);
$token = urldecode($token);

// Preparar consulta para verificar el usuario con el email y el token
$miConsulta = $miPDO->prepare('
    SELECT * FROM usuarios 
    WHERE email = :email AND token = :token AND activo = 0
');
$miConsulta->execute([
    'email' => $email,
    'token' => $token
]);
$usuario = $miConsulta->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    // Activar la cuenta
    $miActualizar = $miPDO->prepare('
        UPDATE usuarios 
        SET activo = 1 
        WHERE email = :email AND token = :token
    ');
    $miActualizar->execute([
        'email' => $email,
        'token' => $token
    ]);
    
    echo 'Estimado usuario, su cuenta se ha activado con éxito';
} else {
    echo 'Parámetros inválidos o cuenta ya activada';
}
?>





