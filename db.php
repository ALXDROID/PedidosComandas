<?php
$DB_HOST = getenv("MYSQLHOST");
$DB_USER = getenv("MYSQLUSER");
$DB_PASSWORD = getenv("MYSQLPASSWORD");
$DB_NAME = getenv("MYSQLDATABASE");
$DB_PORT = getenv("MYSQLPORT");

// Crear conexión
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME, $DB_PORT);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT IDAuto, cantidad, nomCliente, nomProd FROM orden WHERE IDAuto > ? ORDER BY IDAuto ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lastId);  // Evita inyecciones SQL
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

echo json_encode($rows);

$stmt->close();
$conn->close();
?>
