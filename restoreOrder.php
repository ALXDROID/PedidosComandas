<?php
$DBURL = "mysql://root:kUfcvmpoRcVdxKgpoioLkbTIxmEizFwt@autorack.proxy.rlwy.net:23890/railway"
$db = parse_url($DBURL);    
$host = $db['host'];
$user = $db['user'];
$password = $db['pass'];
$dbname = ltrim($db['path'], '/');
$port = $db['port'];

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
