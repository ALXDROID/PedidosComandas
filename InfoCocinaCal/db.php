<?php

// Cargar el archivo .env usando la librería dotenv
/* require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Obtener las credenciales desde las variables de entorno
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME']; */
$host = 'sql10.freesqldatabase.com';
$port = '3306';
$user = 'sql10771425';
$password = 'YBAhWgGML5';
$dbname = 'sql10771425';
// Establecer la conexión a MySQL utilizando mysqli
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Verificar si hay error en la conexión
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Consulta SQL para obtener los datos de la tabla 'orden'
$sql = "SELECT IDAuto, cantidad, nomCliente, nomProd FROM orden";
$result = $conn->query($sql);

// Verificar si hay resultados y almacenarlos en un array
$rows = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;  // Almacenar cada fila en el array
    }
}

// Convertir los resultados a formato JSON y devolverlos
echo json_encode($rows);

// Cerrar la conexión
$conn->close();

?>
