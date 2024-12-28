<?php
$DB_HOST = $_ENV["DB_HOST"];
$DB_USER = $_ENV["DB_USER"];  // Cambia esto si tienes un nombre de usuario diferente
$DB_PASSWORD = $_ENV["DB_PASSWORD"];  // Cambia esto si tienes una contraseña
$DB_NAME = $_ENV["DB_NAME"];
$DB_PORT = $_ENV["DB_PORT"];     // Cambia esto por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME, $DB_PORT);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consulta SQL
$sql = "SELECT * FROM orden ";  // Cambia esto por tu consulta SQLcantidad, nomCliente,
$result = $conn->query($sql);

$rows = array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} else {
    $rows = array("message" => "0 results");
}

echo json_encode($rows);

$conn->close();
?>
