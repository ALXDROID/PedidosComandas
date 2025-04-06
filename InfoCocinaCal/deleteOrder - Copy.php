<?php
// Conexión directa a MySQL (sin dotenv)
$host = 'sql10.freesqldatabase.com';
$port = '3306';
$user = 'sql10771425';
$password = 'YBAhWgGML5';
$dbname = 'sql10771425';
// Establecer la conexión a MySQL utilizando mysqli

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";  // Se agrega 'charset=utf8' para evitar problemas con caracteres especiales

// Establecer la conexión
try {
    // Crear la conexión PDO
    $conn = new PDO($dsn, $user, $password);
    
    // Configurar el manejo de errores de PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Esto permite ver los errores de SQL
} catch (PDOException $e) {
    // Si no se puede conectar, mostrar el error en formato JSON
    echo json_encode(array("error" => "Connection failed: " . $e->getMessage()));
    exit();  // Detiene la ejecución si no se puede conectar
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
//$conn = null;
$conn->close();
?>
