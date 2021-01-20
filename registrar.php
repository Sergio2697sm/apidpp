<?php

include "conexion.php";
include "config.php";
include "respuestas.php";

$dbConn = connect($db);

header('Access-Control-Allow-Origin: *');
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
// header('content-type: application/json; charset=utf-8');
//En caso de que ninguna de las opciones anteriores se haya ejecutado

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
    $Contrasena =  md5($_POST["Contrasena"]);
    //$Rol =  $_POST["Rol"];
    
    $errores = [];
    
    // if (!empty($NombreUsuario)) {
        //     $consulta = "SELECT Count(NombreUsuario) FROM usuarios WHERE NombreUsuario = '$NombreUsuario'";
        
        //     echo $consulta;
        //     $sql = $dbConn->prepare($consulta);
        //     $sql->execute();
        //     $resultado = $sql->setFetchMode(PDO::FETCH_ASSOC);
        
        //     if ($resultado > 0) {
            //         $errores[] = "El nombre de usuario tiene que ser diferente</br>";
            //     }
            // }
            
            
            
            
            if ($NombreUsuario == '') {
                $errores[] = "Nombre de usuario no valido</br>";
            }
            
            if (strlen($NombreUsuario) <= 5) {
                $errores[] = "El usuario tiene que tener las de 5 caracteres</br>";
            }
            
            if ($Contrasena == '') {
                $errores[] = "Contraseña no valido</br>";
            }
            
            if (strlen($Contrasena) <= 5) {
                $errores[] = "La contraseña tiene que tener mas de 5 caracteres</br>";
            }
            
            if ($correo == '') {
                $errores[] = "E correo no puede estar vacio</br>";
            }
            
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $errores[] = "Correo no valido</br>";
            }
            
            if ($errores) {
                error_400($errores);
            } else {
                $sql = "INSERT INTO usuarios (correo,NombreUsuario,Contrasena,Rol) VALUES (:correo,:NombreUsuario,:Contrasena,'')";
                $statement = $dbConn->prepare($sql);
                // bindAllValues($statement, $input);
                $statement->bindValue(':correo', $correo);
                $statement->bindValue(':NombreUsuario', $NombreUsuario);
                $statement->bindValue(':Contrasena', $Contrasena);
                
                
                $statement->execute();
                $parametros = array($correo, $NombreUsuario, $Contrasena);
                $postId = $dbConn->lastInsertId();
                if ($postId) {
                    $input['id'] = $postId;
                    array_unshift($parametros, $input['id']);
                    header("HTTP/1.1 200 OK");
                    echo json_encode($parametros);
                    exit();
                }else {
                    error_log("Error");
                }
            }
        }
        
        // header("HTTP/1.1 400 Bad Request");
        ?>