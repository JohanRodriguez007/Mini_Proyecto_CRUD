<?php
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

        // Cabecera
        $headers = [
            'From' => 'curso@php.com',
            'Content-type' => 'text/plain; charset=utf-8'
        ];
        // Variables para el email
        $emailEncode = urlencode($email);
        $tokenEncode = urlencode($token);
        // Texto del email
        $textoEmail = "
           Hola!\n 
           Gracias por registrate en la mejor plataforma de internet, demuestras inteligencia.\n
           Para activar entra en el siguiente enlace:\n
           http://midomino.com/verificar-cuenta.php?email=$emailEncode&token=$tokenEncode
            ";
        // Envio del email
        mail($email, 'Activa tu cuenta', 'Gracias por suscribirte', $headers);

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
