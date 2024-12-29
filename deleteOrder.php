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



// Establecer la conexión
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID de la orden a eliminar
$orderId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($orderId > 0) {
    // Mover la orden a la tabla deleted_orders
    $moveSql = "INSERT INTO deleted_orders (IDnoauto, cantidad, nomCliente, nomProd)
                  SELECT IDAuto, cantidad, nomCliente, nomProd FROM orden WHERE IDAuto = $orderId";
    
    if ($conn->query($moveSql) === TRUE) {
        // Eliminar la orden de la tabla ordenes
        $deleteSql = "DELETE FROM orden WHERE IDAuto = $orderId";
        if ($conn->query($deleteSql) === TRUE) {
            echo json_encode(["message" => "Order moved and deleted successfully"]);
        } else {
            echo json_encode(["message" => "Error deleting record: " . $conn->error]);
        }
    } else {
        echo json_encode(["message" => "Error moving record: " . $conn->error]);
    }
} else {
    echo json_encode(["message" => "Invalid order ID"]);
}

$conn->close();
?>
