<?php 
    $host ='127.0.0.1';
    $BD ='mini_proyecto';
    $usuario ='root';
    $clave ='';

    $hostPDO="mysql:host=$host;dbname=$BD;";
    $tuPDO=new PDO($hostPDO,$usuario,$clave);

    $id=isset($_REQUEST['id'])? $_REQUEST['id']: null;

    $eliminar = $tuPDO->prepare('DELETE FROM estudiantes WHERE id = :id');

    $eliminar->execute([
        'id'=>$id
    ]);

    header('Location:consultasBD.php');
?>