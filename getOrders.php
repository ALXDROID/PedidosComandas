<?php

// Cargar el archivo .env usando la librería dotenv
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obtener las credenciales desde las variables de entorno
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];




$conn = new mysqli($host, $user, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    // Devolver error en formato JSON
    echo json_encode(array("error" => "Connection failed: " . $conn->connect_error));
    exit();
}

// Obtener el valor de 'lastId' de la solicitud GET (si existe)
$lastId = isset($_GET['lastId']) ? (int)$_GET['lastId'] : 0;

// Consulta SQL para obtener registros nuevos
$sql = "SELECT IDAuto, cantidad, nomCliente, nomProd FROM orden WHERE IDAuto > ? ORDER BY IDAuto ASC";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lastId); // Usamos 'i' para entero
$stmt->execute();
$result = $stmt->get_result();

// Verificar si la consulta se ejecutó correctamente
if (!$result) {
    echo json_encode(array("error" => "Query failed: " . $conn->error));
    exit();
}

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
