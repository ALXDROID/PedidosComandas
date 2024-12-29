<?php
$DB_HOST = getenv("MYSQLHOST");
$DB_USER = getenv("MYSQLUSER");
$DB_PASSWORD = getenv("MYSQLPASSWORD");
$DB_NAME = getenv("MYSQLDATABASE");
$DB_PORT = getenv("MYSQLPORT");

// Crear conexión
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME, $DB_PORT);

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
