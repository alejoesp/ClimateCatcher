<?php
function insertarDatos($CodigoEstacion, $humidity, $temperature, $luminosity, $pressure) {
    global $pdo;
    try {
date_default_timezone_set('America/Argentina/Buenos_Aires');
$currentDateTime = date('Y-m-d H:i:s');

$stmt = $pdo->prepare("INSERT INTO estacion" . $CodigoEstacion . " (Humedad, Temperatura, Luminosidad, PresionAtmosferica, FechaHora) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$humidity, $temperature, $luminosity, $pressure, $currentDateTime]);

        return true;
    } catch(PDOException $e) {
        return $e->getMessage();
    }
}
?>

