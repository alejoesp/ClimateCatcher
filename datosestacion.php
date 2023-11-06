<?php
include 'conexion.php';

file_put_contents('log.txt', 'Accedido en: ' . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

$response = [
    "status" => "error",
    "message" => "Datos no recibidos"
];

if(isset($_GET['api_key'], $_GET['humidity'], $_GET['temperature'], $_GET['luminosity'], $_GET['pressure'])) {
    
    $api_key = $_GET['api_key'];
    $humidity = $_GET['humidity'];
    $temperature = $_GET['temperature'];
    $luminosity = $_GET['luminosity'];
    $pressure = $_GET['pressure'];

    $api_key = filter_var($api_key, FILTER_SANITIZE_STRING);
    $humidity = filter_var($humidity, FILTER_VALIDATE_FLOAT);
    $temperature = filter_var($temperature, FILTER_VALIDATE_FLOAT);
    $luminosity = filter_var($luminosity, FILTER_VALIDATE_INT); // Asumiendo que luminosidad es un valor entero del 0 al 100
    $pressure = filter_var($pressure, FILTER_VALIDATE_FLOAT);

    if($api_key && $humidity !== false && $temperature !== false && $luminosity !== false && $pressure !== false) {
        $stmt = $pdo->prepare("SELECT CodigoEstacion FROM estaciones WHERE CodigoEstacion = ?");
        $stmt->execute([$api_key]);
        $estacion = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($estacion) {
            $response['status'] = "success";
            $response['message'] = "API key válida.";
            $response['estacion'] = $estacion['CodigoEstacion'];
            include 'insertardatos.php';
            $insertStatus = insertarDatos($estacion['CodigoEstacion'], $humidity, $temperature, $luminosity, $pressure);
            
            if($insertStatus === true) {
                $response['message'] = "Datos insertados con éxito.";
            } else {
                $response['message'] = "Error al insertar datos: " . $insertStatus;
            }

        } else {
            $response['message'] = "API key inválida o estación no encontrada.";
        }

    } else {
        $response['message'] = "Datos inválidos.";
    }

} else {
    $response['message'] = "No se recibieron todos los datos necesarios.";
}

header("Content-Type: application/json");
echo json_encode($response);
?>
