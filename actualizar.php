<?php
    $host ='127.0.0.1';
    $BD ='mini_proyecto';
    $usuario ='root';
    $clave ='';
    $id=isset($_REQUEST['id']) ? $_REQUEST['id']: null;
    $nombre=isset($_REQUEST['nombre']) ? $_REQUEST['nombre']: null;
    $apellido=isset($_REQUEST['apellido']) ? $_REQUEST['apellido']: null;
    $documento=isset($_REQUEST['documento']) ? $_REQUEST['documento']: null;
    $email=isset($_REQUEST['email']) ? $_REQUEST['email']: null;
    $ciudad=isset($_REQUEST['ciudad']) ? $_REQUEST['ciudad']: null;

    $hostPDO="mysql:host=$host;dbname=$BD;";
    $tuPDO=new PDO($hostPDO,$usuario,$clave);

    if($_SERVER['REQUEST_METHOD'] == "POST" ) {
        $actualizar = $tuPDO-> prepare('UPDATE estudiantes SET nombre = :nombre, apellido = :apellido, documento = :documento, email = :email, ciudad = :ciudad WHERE id = :id');

        $actualizar->execute (
            [
                'id' => $id,
                'nombre'=>$nombre,
                'apellido'=>$apellido,
                'documento'=>$documento,
                'email'=>$email,
                'ciudad'=>$ciudad
            ]
            );
            header('Location:consultasBD.php');
    } else {
        
        $consulta = $tuPDO -> prepare(
            'SELECT * FROM estudiantes WHERE id = :id;'
        );
        
        $consulta-> execute(
            [
                'id' => $id
            ]);
        }

        $resultado = $consulta->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar</title>
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
    <form method="post">
            <label for ="nombre">Nombre</label>
            <input id="nombre" type="text" name="nombre" value="<?=$resultado['nombre']?>">
            <label for ="apellido">Apellido<label>
            <input id="apellido" type="text" name="apellido" value="<?=$resultado['apellido']?>">
            <label for ="documento">Documento<label>
            <input id="documento" type="text" name="documento" value="<?=$resultado['documento']?>">
            <label for ="email">Email<label>
            <input id="email" type="email" name="email" value="<?=$resultado['email']?>">
            <label for ="ciudad">Ciudad<label>
            <input id="ciudad" type="text" name="ciudad" value="<?=$resultado['ciudad']?>">
            <input type="hidden" id="id" name="id" value="<?=$resultado['id']?>">
            <input type="submit" value="Guardar">
    </form>
</body>
</html>