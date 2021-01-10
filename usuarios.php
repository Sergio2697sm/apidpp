<?php
include "conexion.php";
include "config.php";

$dbConn = connect($db);

header('Access-Control-Allow-Origin: *');
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// header('content-type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        //Mostrar un post
        $sql = $dbConn->prepare("SELECT * FROM usuarios where id=:id");
        $sql->bindValue(':id', $_GET['id']);
        $sql->execute();
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetch(PDO::FETCH_ASSOC));
        exit();
    } else {
        //Mostrar lista de post
        $sql = $dbConn->prepare("SELECT * FROM usuarios");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($sql->fetchAll());
        exit();
    }
}


//registrar usuarios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // $input = '$_POST';
    
    $correo =  $_POST["correo"];
    $NombreUsuario =  $_POST["NombreUsuario"];
    $Contrasena =  $_POST["Contrasena"];
    $Rol =  $_POST["Rol"];

    $sql = "INSERT INTO usuarios (correo,NombreUsuario,Contrasena,Rol) VALUES (:correo,:NombreUsuario,:Contrasena,:Rol)";
    $statement = $dbConn->prepare($sql);
    // bindAllValues($statement, $input);
    $statement->bindValue(':correo', $correo);
    $statement->bindValue(':NombreUsuario', $NombreUsuario);
    $statement->bindValue(':Contrasena', $Contrasena);
    $statement->bindValue(':Rol', $Rol);


    $statement->execute();
    $parametros = array($correo, $NombreUsuario, $Contrasena, $Rol);
    $postId = $dbConn->lastInsertId();
    if ($postId) {
        $input['id'] = $postId;
        array_unshift($parametros, $input['id']);
        header("HTTP/1.1 200 OK");
        echo json_encode($parametros);
        exit();
    }
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
