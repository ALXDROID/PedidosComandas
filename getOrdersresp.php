<?php

// Conexión directa a MySQL (sin dotenv)
$host = "localhost";       // Dirección del servidor MySQL
$port = "3306";            // Puerto de MySQL
$user = "root";            // Usuario por defecto en XAMPP
$password = "";            // Contraseña de la base de datos (vacía por defecto en XAMPP)
$dbname = "puntosystem";   // Nombre de la base de datos

// Establecer la conexión a MySQL usando PDO
$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";  // Se agrega 'charset=utf8' para evitar problemas con caracteres especiales

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

// Obtener el valor de 'lastId' de la solicitud GET (si existe)
$lastId = isset($_GET['lastId']) ? (int)$_GET['lastId'] : 0;  // Convertir a entero, con valor predeterminado de 0

// Consulta SQL para obtener registros nuevos desde el último ID
$sql = "SELECT IDAuto, cantidad, nomCliente, nomProd FROM orden WHERE IDAuto > $lastId ORDER BY IDAuto ASC";

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);
$stmt->bindParam('$lastId', $lastId, PDO::PARAM_INT);  // Vincula el parámetro lastId
$stmt->execute();  // Ejecuta la consulta
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Obtener los resultados en un arreglo asociativo

// Verificar si hay resultados y devolverlos en formato JSON
if ($result) {
    echo json_encode($result);  // Si hay resultados, devolverlos como JSON
} else {
    echo json_encode(array("message" => "0 results"));  // Si no hay resultados, enviar mensaje
}

// Cerrar la conexión
//$conn = null;
$conn->close();
?>
