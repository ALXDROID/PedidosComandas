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
