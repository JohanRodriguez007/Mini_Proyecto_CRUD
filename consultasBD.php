<?php
// Activa las sesiones
session_start();
// Comprueba si existe la sesión 'email', en caso contrario vuelve a la página de identificación
if (!isset($_SESSION['email'])) {
    header('Location: identificarse.php');
    die();
}
?>

<?php
    $host ='127.0.0.1';
    $BD ='mini_proyecto';
    $usuario ='root';
    $clave ='';

    $hostPDO="mysql:host=$host; dbname=$BD;";
    $tuPDO=new PDO($hostPDO,$usuario,$clave);
    $consulta= $tuPDO->Prepare('SELECT*FROM estudiantes;');

    $consulta->execute();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BD consulta</title>
        <style>
            table {
                    border-collapse: collapse;
                    width: 100%;
                    margin: 20px auto;
                    font-size: 16px;
                    font-family: Arial, sans-serif;
                }

                    th, td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: center;
                    height: 45px;
                }

                    th {
                    background-color: #f0f0f0;
                    font-weight: bold;
                }

                    tr:nth-child(even) {
                    background-color: #f9f9f9;
                }

                    tr:hover {
                    background-color: #ccc;
                }

                    .button {
                    background-color: #4CAF50;
                    color: #fff;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    text-decoration: none;
                }

                    .button:hover {
                    background-color: #3e8e41;
                }

                    .mis-botones {
                        display: flex;
                        flex-direction: row;
                        justify-content: space-around;
                    }
        </style>
</head>
<body>

    <div class="mis-botones">
    <p><a class="button" href="insertar.php"> Crear</a></p>
    <p><a class="button" href="logout.php"> Cerrar sesión</a></p>
    </div>
 

    <table>
        <tr>
            <th>ID</th>
            <th>NOMBRE</th>
            <th>APELLIDO</th>
            <th>DOCUMENTO</th>
            <th>EMAIL</th>
            <th>CIUDAD</th>
            <th colspan="2">ACCIONES</th>
        </tr>
    <?php foreach($consulta as $key =>$valor): ?>
        <tr>
            <td><?= $valor['id']; ?></td>
            <td><?= $valor['nombre']; ?></td>
            <td><?= $valor['apellido']; ?></td>
            <td><?= $valor['documento']; ?></td>
            <td><?= $valor['email']; ?></td>
            <td><?= $valor['ciudad']; ?></td>
            <td><a class="button" href="actualizar.php?id=<?=$valor['id']?>">Modificar</a></td>
            <td><a class="button" href="borrar.php?id=<?=$valor['id']?>">Borrar</a></td>
        </tr>
        <?php endforeach;?>
    </table>
</body>
</html>