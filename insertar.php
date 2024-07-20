<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $nombre=isset($_REQUEST['nombre']) ? $_REQUEST['nombre']: null;
        $apellido=isset($_REQUEST['apellido']) ? $_REQUEST['apellido']: null;
        $documento=isset($_REQUEST['documento']) ? $_REQUEST['documento']: null;
        $email=isset($_REQUEST['email']) ? $_REQUEST['email']: null;
        $ciudad=isset($_REQUEST['ciudad']) ? $_REQUEST['ciudad']: null;

        $host ='127.0.0.1';
        $BD ='mini_proyecto';
        $usuario ='root';
        $clave ='';
        $hostPDO="mysql:host=$host;dbname=$BD;";
        $tuPDO=new PDO($hostPDO,$usuario,$clave);

        $insertar=$tuPDO->prepare('INSERT INTO estudiantes(nombre,apellido,documento,email,ciudad)
                                    VALUES(:nombre,:apellido,:documento,:email,:ciudad)');
        
        $insertar->execute(array(
                            'nombre'=>$nombre,
                            'apellido'=>$apellido,
                            'documento'=>$documento,
                            'email'=>$email,
                            'ciudad'=>$ciudad
                            )
        );

        header('Location:consultasBD.php');

    }  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar</title>
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
    <form action = " " method="post">
        <label for ="nombre">Nombre</label>
            <input id="nombre" type="text" name="nombre">
            <label for ="apellido">Apellido<label>
            <input id="apellido" type="text" name="apellido">
            <label for ="documento">Documento<label>
            <input id="documento" type="text" name="documento">
            <label for ="email">Email<label>
            <input id="email" type="email" name="email">
            <label for ="ciudad">Ciudad<label>
            <input id="ciudad" type="text" name="ciudad">
            <input type="submit" value="Guardar">
    </form>
</body>
</html>