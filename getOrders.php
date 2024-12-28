
<?php
$servername = "localhost";
$username = "root";  // Cambia esto si tienes un nombre de usuario diferente
$password = "";  // Cambia esto si tienes una contraseña
$dbname = "test3";  // Cambia esto por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener la última ID de la solicitud
$lastId = isset($_GET['lastId']) ? (int)$_GET['lastId'] : 0;

// Protegerse contra inyecciones SQL (aunque $lastId ya es un entero, es buena práctica)
//$lastId = $conn->real_escape_string($lastId);

// Consulta SQL para obtener registros nuevos
$sql = "SELECT IDAuto, cantidad, nomCliente, nomProd FROM orden WHERE IDAuto > $lastId ORDER BY IDAuto ASC";
$result = $conn->query($sql);

// Verificar si la consulta se ejecutó correctamente
// if (!$result) {
//     die(json_encode(array("error" => "Query failed: " . $conn->error)));
// }

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
