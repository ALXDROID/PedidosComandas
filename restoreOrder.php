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

// Obtener el ID de la orden a restaurar
$orderId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($orderId >= 0) {
    // Mover la orden de regreso a la tabla ordenes
    $moveSql = "INSERT INTO orden (IDAuto, cantidad, nomCliente, nomProd)
                 SELECT IDnoauto, cantidad, nomCliente, nomProd FROM deleted_orders WHERE IDnoauto = $orderId";
    
    if ($conn->query($moveSql) === TRUE) {
        // Eliminar la orden de la tabla deleted_orders
        $deleteSql = "DELETE FROM deleted_orders WHERE IDnoauto = $orderId";
        if ($conn->query($deleteSql) === TRUE) {
            echo json_encode(["message" => "Order restored successfully"]);
        } else {
            echo json_encode(["message" => "Error deleting record from deleted_orders: " . $conn->error]);
        }
    } else {
        echo json_encode(["message" => "Error moving record to ordenes: " . $conn->error]);
    }
} else {
    echo json_encode(["message" => "Invalid order ID"]);
}

$conn->close();
?>
