<?php
$servername = "localhost";
$username = "root";  // Cambia esto si tienes un nombre de usuario diferente
$password = "";  // Cambia esto si tienes una contraseña
$dbname = "test3";  // Cambia esto por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

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
