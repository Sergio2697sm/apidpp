<?php
include "conexion.php";
include "config.php";

$dbConn = connect($db);

header('Access-Control-Allow-Origin: *');
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// header('content-type: application/json; charset=utf-8');


 //login usuarios
 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $NombreUsuario =  $_POST["NombreUsuario"];
    $Contrasena =  md5($_POST["Contrasena"]);

    $errores = [];

    if ($NombreUsuario == '') {
        $errores[] = "Nombre de usuario no valido</br>";
    }

    if ($Contrasena == '') {
        $errores[] = "Contraseña no valido</br>";
    }

    if ($errores) {
        error_400($errores);
    } else {
        // $sql = "INSERT INTO usuarios (correo,NombreUsuario,Contrasena) VALUES (:correo,:NombreUsuario,:Contrasena)";
        $sql = "SELECT NombreUsuario FROM usuarios WHERE NombreUsuario = :NombreUsuario AND Contrasena = :Contrasena";
        $statement = $dbConn->prepare($sql);
        $statement->bindValue(':NombreUsuario', $NombreUsuario);
        $statement->bindValue(':Contrasena', $Contrasena);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        header("HTTP/1.1 200 OK");
        echo json_encode($statement->fetchAll());
        $_SESSION["NombreUsuario"] = $NombreUsuario;
        exit();
    }
}

//En caso de que ninguna de las opciones anteriores se haya ejecutado
header("HTTP/1.1 400 Bad Request");
