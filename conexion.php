<?php
$host = "localhost";
$dbname = "u598935066_climainn";
$username = "u598935066_facu";
$password = "Climainn2023";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: " . $e->getMessage());
}
?>
