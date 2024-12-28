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
