<?php 
// include 'config.php';
function connect($db) {
    try {
        $conn = new PDO("mysql:host={$db['host']};dbname={$db['db']};charset=utf8", $db['username'], $db['password']);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $conn;
    }catch(PDOException $exception) {
        exit($exception->getMessage());
    }
}

//Obtener parametros para updates
 function getParams($input)
 {
    $filterParams = [];
    foreach($input as $param => $value)
    {
            $filterParams[] = "$param=:$param";
    }
    return implode(", ", $filterParams);
	}

  // function bindAllValues($statement, $params)
  // {
	// 	foreach($params as $param => $value)
  //   {
	// 			$statement->bindValue(':'.$param, $value);
	// 	}
	// 	return $statement;
  //  }
