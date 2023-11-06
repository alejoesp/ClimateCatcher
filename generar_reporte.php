<?php
require('fpdf/fpdf.php');
include 'conexion.php';
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}
$idUsuario = $_SESSION['idUsuario'];
$estacion = $_SESSION['estacion'];
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Insertar imagen
$pdf->Image('imagen/logo.jpeg', 10, 10, 30);
$pdf->Ln(20); // Espaciado

// Título del reporte
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(190, 10, 'Reporte de Datos ClimateCatcher', 0, 1, 'C');

$pdf->Ln(10); // Espaciado

// Encabezado de tabla
$pdf->SetFillColor(51, 122, 183);
$pdf->SetTextColor(255);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Fecha y Hora', 1, 0, 'C', true);
$pdf->Cell(25, 10, 'Humedad', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Temperatura', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Luminosidad', 1, 0, 'C', true);
$pdf->Cell(42, 10, 'Presion Atmosferica', 1, 1, 'C', true);

// Datos de la tabla
$stmt = $pdo->prepare("SELECT * FROM estacion" . $estacion);
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0);
foreach($datos as $dato) {
    $pdf->Cell(60, 10, $dato['FechaHora'], 1, 0, 'C');
    $pdf->Cell(25, 10, $dato['Humedad'], 1, 0, 'C');
    $pdf->Cell(30, 10, $dato['Temperatura'], 1, 0, 'C');
    $pdf->Cell(30, 10, $dato['Luminosidad'], 1, 0, 'C');
    $pdf->Cell(42, 10, $dato['PresionAtmosferica'], 1, 1, 'C');
}

// Cabeceras de descarga
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="reporte.pdf"');

// Generar PDF
$pdf->Output('D');
?>
