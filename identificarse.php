<?php 
    //======================================================================
    // PROCESAR FORMULARIO 
    //======================================================================
    
    //-----------------------------------------------------
    // Variables
    //-----------------------------------------------------
    $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;
    $password = isset($_REQUEST['contrasenya']) ? $_REQUEST['contrasenya'] : null;
    $errores = [];

    // Comprobamos que nos llega los datos del formulario
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        //-----------------------------------------------------
        // COMPROBAR SI LA CUENTA ESTA ACTIVA
        //-----------------------------------------------------
        // Conecta con base de datos
        $BD ='mini_proyecto';
        $miPDO = new PDO("mysql:host=127.0.0.1; dbname=$BD;", 'root', '');
        // Prepara SELECT para obtener la contraseña almacenada del usuario
        $miConsulta = $miPDO->prepare('SELECT activo, password FROM usuarios WHERE email = :email;');
        // Ejecuta consulta
        $miConsulta->execute([
            'email' => $email
        ]);
        // Guardo el resultado
        $resultado = $miConsulta->fetch();
        if ($resultado !== false) {
            if ((int) $resultado['activo'] !== 1) {
                $errores[] = 'Tu cuenta aún no esta activa. ¿Has comprobado tu bandeja de correo?';
            } else {
                //-----------------------------------------------------
                // COMPROBAR LA CONTRASEÑA
                //-----------------------------------------------------
                // Comprobamos si es válida
                if (password_verify($password, $resultado['password'])) {
                    // Si son correctos, creamos la sesión
                    session_start();
                    $_SESSION['email'] = $email;
                    // Redireccionamos a la página segura
                    header('Location: consultasBD.php');
                    die();
                } else {
                    $errores[] = 'El email o la contraseña es incorrecta.';
                }
            }
        } else {
            $errores[] = 'El email no existe en nuestra base de datos.';
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entra</title>
            <style>

                p {
                    text-align: center;
            color: green;
                }

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
            width: 95%; 
            padding: 10px; /
            margin-bottom: 20px; 
            border: 1px solid #ccc; 
        }

        input[type="password"], input[type="contrasenya"] {
            width: 95%; 
            padding: 10px; 
            margin-bottom: 20px; 
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
    <?php if (count($errores) > 0): ?>
    <ul class="errores">
        <?php 
            foreach ($errores as $error) {
                echo '<li>' . $error . '</li>';
            } 
        ?> 
    </ul>
    <?php endif; ?>
    <!-- Mensaje de aviso al registrarte -->
    <?php if(isset($_REQUEST['registrado'])): ?> 
    <p>¡Gracias por registrarte! Revista tu bandeja de correo para activar la cuenta.</p>
    <?php endif; ?> 
    <!-- Mensaje de cuenta activa -->
    <?php if(isset($_REQUEST['activada'])): ?> 
    <p>¡Cuenta activada!</p>
    <?php endif; ?> 
    <!-- Formulario de identificación -->
    <form method="post">
        <p>
            <input type="text" name="email" placeholder="Email"> 
        </p> 
        <p>
            <input type="password" name="contrasenya" placeholder="Contraseña"> 
        </p>
        <p>
            <input type="submit" value="Entrar"> 
        </p>
    </form>
</body>
</html>
