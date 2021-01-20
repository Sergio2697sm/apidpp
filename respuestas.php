<?php 
$dbConn = connect($db);

$errores = [
    'status' =>"Error",
    'result' => array()
];

function error_400($error) {

    $errores['status'] = 'error';
    $errores['result'] = array(
        "error_id" => '400',
        "error_msg" => $error
    );

    echo json_encode($errores);
    
}

// function comprobarNombre($NombreUsuario) {
//   $consulta = "SELECT NombreUsuario FROM usuarios WHERE NombreUsuario = $NombreUsuario";

//     echo $consulta;
//     $sql= $dbConn->prepare($consulta);
//     $sql->execute();
//     $resultado=$sql->setFetchMode(PDO::FETCH_ASSOC);

//     error_400($resultado) ;
// }


?>