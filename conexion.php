<?php
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

if (!$dbname || !$username || !$password) {
    throw new RuntimeException('Faltan variables de entorno para la conexión a la base de datos.');
}

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    throw new RuntimeException('No se pudo establecer la conexión con la base de datos.');
}
?>
