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
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully!";
$conn->close();
?>
