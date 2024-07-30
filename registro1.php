<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
//======================================================================
// PROCESAR FORMULARIO 
//======================================================================

//-----------------------------------------------------
// Funciones Para Validar
//-----------------------------------------------------

function validar_requerido(string $texto): bool
{
    return !(trim($texto) == '');
}

function validar_email(string $texto): bool
{
    return filter_var($texto, FILTER_VALIDATE_EMAIL);
}

//-----------------------------------------------------
// Variables
//-----------------------------------------------------
$errores = [];
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';

// Comprobamos si nos llega los datos por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    //-----------------------------------------------------
    // Validaciones
    //-----------------------------------------------------
    // Email
    if (!validar_requerido($email)) {
        $errores[] = 'Campo Email obligatorio.';
    }

    if (!validar_email($email)) {
        $errores[] = 'Campo Email no tiene un formato válido';
    }

    // Contraseña
    if (!validar_requerido($password)) {
        $errores[] = 'Campo Contraseña obligatorio.';
    }

    /* Verificar que no existe en la base de datos el mismo email */
    // Conecta con base de datos
    $BD ='mini_proyecto';
    $miPDO = new PDO("mysql:host=127.0.0.1; dbname=$BD;", 'root', '');
    // Cuenta cuantos emails existen
    $miConsulta = $miPDO->prepare('SELECT COUNT(*) as length FROM usuarios WHERE email = :email;');
    // Ejecuta la busqueda
    $miConsulta->execute([
        'email' => $email
    ]);
    // Recoge los resultados
    $resultado = $miConsulta->fetch();
    // Comprueba si existe
    if ((int) $resultado['length'] > 0) {
        $errores[] = 'La dirección de email ya esta registrada.';
    }

    //-----------------------------------------------------
    // Crear cuenta
    //-----------------------------------------------------
    if (count($errores) === 0) {
        /* Registro En La Base De Datos */

        // Prepara INSERT
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $miNuevoRegistro = $miPDO->prepare('INSERT INTO usuarios (email, password, activo, token) VALUES (:email, :password, :activo, :token);');
        // Ejecuta el nuevo registro en la base de datos
        $miNuevoRegistro->execute([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'activo' => 0,
            'token' => $token
        ]);

        /* Envío De Email Con Token */

        // Configuración de PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Nivel de depuración
            $mail->isSMTP(); // Usar SMTP
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
            $mail->SMTPAuth = true; // Habilitar autenticación SMTP
            $mail->Username = 'depruebac310@gmail.com'; // Usuario SMTP
            $mail->Password = 'vjyeitfrmywcqgoz'; // Contraseña SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar cifrado TLS
            $mail->Port = 587; // Puerto TCP para TLS

            // Remitente y destinatarios
            $mail->setFrom('depruebac310@gmail.com', 'Vuestro Servidor');
            $mail->addAddress($email);
            
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Activa tu cuenta';
            $emailEncode = urlencode($email);
            $tokenEncode = urlencode($token);
            $mail->Body = "
            Hola!<br>
            Gracias por registrarte en la mejor plataforma de internet, demuestras inteligencia.<br>
            Para activar tu cuenta, por favor haz clic en el siguiente enlace:<br>
            <a href='http://localhost/mini_proyecto/activar_cuenta.php?email=$emailEncode&token=$tokenEncode'>Activar cuenta</a>";

            $mail->send();
            echo 'Correo enviado';
        } catch (Exception $e) {
            echo 'Mensaje: ' . $mail->ErrorInfo;
        }

        /* Redirección a login.php con GET para informar del envio del email */
        header('Location: identificarse.php?registrado=1');
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title></title>
    <style>
  
        form {
            width: 50%; 
            margin: 40px auto; 
            padding: 20px; 
            border: 1px solid #ccc; 
            border-radius: 10px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }

        
        label {
            display: block; 
            margin-bottom: 10px; 
        }

        input[type="text"], input[type="email"] {
            width: 100%; 
            padding: 10px; 
            margin-bottom: 20px; /
            border: 1px solid #ccc; 
        }

        input[type="submit"] {
            background-color: #4CAF50; 
            color: #fff; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
        }

        input[type="submit"]:hover {
            background-color: #3e8e41; 
        }
        </style>
</head>
<body>
    <!-- Mostramos errores por HTML -->
    <?php if (isset($errores)): ?>
    <ul class="errores">
        <?php 
            foreach ($errores as $error) {
                echo '<li>' . $error . '</li>';
            } 
        ?> 
    </ul>
    <?php endif; ?>
    <!-- Formulario -->
    <form action="" method="post">
        <div>
            <!-- Campo de Email -->
            <label>
                E-mail
                <input type="text" name="email">
            </label>
        </div>
        <div>
            <!-- Campo de Contraseña -->
            <label>
                Contraseña
                <input type="text" name="password">
            </label>
        </div>
        <div>
            <!-- Botón submit -->
            <input type="submit" value="Registrarse">
        </div>
    </form>
</body>
</html>
