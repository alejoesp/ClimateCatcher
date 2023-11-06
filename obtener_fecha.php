<?php
include 'conexion.php';
$estacion = $_POST['estacion'];  // Obtener la estación de la petición AJAX

$stmt = $pdo->prepare("SELECT DISTINCT DATE(FechaHora) as Fecha FROM estacion" . $estacion . " ORDER BY FechaHora DESC");
$stmt->execute();
$fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($fechas);
?>
