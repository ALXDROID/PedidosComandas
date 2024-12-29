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

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$sql = "SELECT IDAuto, cantidad, nomCliente, nomProd FROM orden";
$result = $conn->query($sql);

$rows = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

echo json_encode($rows); // Devuelve los datos como JSON
$conn->close();

?>
