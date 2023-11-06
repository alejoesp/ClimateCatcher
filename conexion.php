<?php
// Definición de parámetros de conexión a la base de datos
$host = "localhost"; // Servidor donde se encuentra alojada la base de datos
$dbname = "xxxxx"; // Nombre de la base de datos a la cual se desea conectar
$username = "xxxxxx"; // Nombre de usuario para la autenticación en la base de datos
$password = "xxxxxxxx"; // Contraseña correspondiente al usuario para la autenticación

try {
    // Creación de un nuevo objeto PDO que facilitará la conexión con la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Establecimiento de atributos para el manejo de errores.
    // PDO::ATTR_ERRMODE: Modo de error (usamos excepciones).
    // PDO::ERRMODE_EXCEPTION: Lanza una excepción cuando ocurre un error en la base de datos.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // En caso de error en la conexión, se detiene la ejecución y se muestra el mensaje de error.
    // La función die() es equivalente a exit(), y detendrá el script inmediatamente.
    die("ERROR: " . $e->getMessage());
}
?>
