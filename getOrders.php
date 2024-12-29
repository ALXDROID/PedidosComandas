
<?php
$DBURL = "mysql://root:kUfcvmpoRcVdxKgpoioLkbTIxmEizFwt@autorack.proxy.rlwy.net:23890/railway"
$db = parse_url($DBURL);    
$host = $db['host'];
$user = $db['user'];
$password = $db['pass'];
$dbname = ltrim($db['path'], '/');
$port = $db['port'];

// Establecer la conexión
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener la última ID de la solicitud
$lastId = isset($_GET['lastId']) ? (int)$_GET['lastId'] : 0;

// Protegerse contra inyecciones SQL (aunque $lastId ya es un entero, es buena práctica)
//$lastId = $conn->real_escape_string($lastId);

// Consulta SQL para obtener registros nuevos
$sql = "SELECT IDAuto, cantidad, nomCliente, nomProd FROM orden WHERE IDAuto > $lastId ORDER BY IDAuto ASC";
$result = $conn->query($sql);

// Verificar si la consulta se ejecutó correctamente
// if (!$result) {
//     die(json_encode(array("error" => "Query failed: " . $conn->error)));
// }

// Preparar el array de resultados
$rows = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} else {
    $rows = array("message" => "0 results");
}

// Convertir a JSON y mostrar los resultados
echo json_encode($rows);

// Cerrar la conexión
$conn->close();
?>
