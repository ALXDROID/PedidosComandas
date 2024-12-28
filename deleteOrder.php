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
