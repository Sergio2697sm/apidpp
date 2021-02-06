<?php 
include "conexion.php";
include "config.php";
include "respuestas.php";


$dbConn = connect($db);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    
    if(isset($_FILES["foto"]['name'])) {
        $tipoArchivo = $_FILES["foto"]["type"];
        $nombreArchivo = $_FILES["foto"]["name"];
        $tamañoArchivo = $_FILES["foto"]["size"];
        $imagenSubida =fopen($_FILES["foto"]["tmp_name"],'r');
        $binariosImagen = fread($imagenSubida,$tamañoArchivo);
        // $binariosImagen = mysqli_escape_string($dbConn,$binariosImagen);
    }
    // echo $nombreArchivo;

    $sql = "INSERT INTO noticias (TItulo,Descripcion,Imagen) VALUES (:titulo,:descripcion,:imagen)";
    $statement = $dbConn->prepare($sql);
    $statement->bindValue(':titulo',$titulo);
    $statement->bindValue(':descripcion',$descripcion);
    $statement->bindValue('imagen',$binariosImagen);

    $statement->execute();

    $parametros = array($titulo, $descripcion, $binariosImagen);
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
