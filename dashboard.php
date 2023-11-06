<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['idUsuario'])) {
    header("Location: login.php");
    exit;
}
$idUsuario = $_SESSION['idUsuario'];
$estacion = $_SESSION['estacion'];
// Variables para paginación
$registrosPorPagina = 10;
$paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM estacion" . $estacion);
$stmtCount->execute();
$totalRegistros = $stmtCount->fetchColumn();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

$stmt = $pdo->prepare("SELECT * FROM estacion" . $estacion . " ORDER BY FechaHora DESC LIMIT :inicio, :registrosPorPagina");
$stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindParam(':registrosPorPagina', $registrosPorPagina, PDO::PARAM_INT);
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Consulta para datos más recientes
$stmtLatest = $pdo->prepare("SELECT * FROM estacion" . $estacion . " ORDER BY FechaHora DESC LIMIT 1");
$stmtLatest->execute();
$latestData = $stmtLatest->fetch();

// Inicialización de variables para evitar advertencias;
$lastTemp = $lastHum = $lastPresion = $lastLuminosidad = 0;
$avgTemp = $avgHum = $avgPresion = $avgLuminosidad = 0;
$minTemp = $minHum = $minPresion = $minLuminosidad = PHP_INT_MAX;
$maxTemp = $maxHum = $maxPresion = $maxLuminosidad = PHP_INT_MIN;

if ($latestData) {
    $lastTemp = $latestData['Temperatura'];
    $lastHum = $latestData['Humedad'];
    $lastPresion = $latestData['PresionAtmosferica'];
    $lastLuminosidad = $latestData['Luminosidad'];
}
try {
    $query = "SELECT 
                MIN(Temperatura) AS minTemp, MAX(Temperatura) AS maxTemp, AVG(Temperatura) AS avgTemp, 
                MIN(Humedad) AS minHum, MAX(Humedad) AS maxHum, AVG(Humedad) AS avgHum, 
                MIN(Luminosidad) AS minLuminosidad, MAX(Luminosidad) AS maxLuminosidad, AVG(Luminosidad) AS avgLuminosidad, 
                MIN(PresionAtmosferica) AS minPresion, MAX(PresionAtmosferica) AS maxPresion, AVG(PresionAtmosferica) AS avgPresion 
              FROM estacion{$estacion}";

    $stmtAgg = $pdo->query($query);
    $aggResult = $stmtAgg->fetch(PDO::FETCH_ASSOC);

    if ($aggResult) {
        $minTemp = $aggResult['minTemp'];
        $maxTemp = $aggResult['maxTemp'];
        $avgTemp = $aggResult['avgTemp'];

        $minHum = $aggResult['minHum'];
        $maxHum = $aggResult['maxHum'];
        $avgHum = $aggResult['avgHum'];

        $minLuminosidad = $aggResult['minLuminosidad'];
        $maxLuminosidad = $aggResult['maxLuminosidad'];
        $avgLuminosidad = $aggResult['avgLuminosidad'];

        $minPresion = $aggResult['minPresion'];
        $maxPresion = $aggResult['maxPresion'];
        $avgPresion = $aggResult['avgPresion'];
    }
} catch (PDOException $e) {
    // Manejo de la excepción
    error_log('PDOException - ' . $e->getMessage(), 0);
}
// Cálculo del número total de páginas
$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM estacion" . $estacion);
$stmtTotal->execute();
$totalRegistros = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

if (isset($_POST['selected_date'])) {
    $selected_date = $_POST['selected_date'];   
    $stmt = $pdo->prepare("SELECT * FROM estacion" . $estacion . " WHERE DATE(FechaHora) = ?");
    $stmt->execute([$selected_date]);
    $datos = $stmt->fetchAll();
}
$eliminadoExitosamente = false;
if (isset($_POST['eliminarCuenta'])) {
    $stmt2 = $pdo->prepare("DELETE FROM estacion" . $estacion);
    $stmt2->execute();

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE IDUsuario = ?");
    $stmt->execute([$idUsuario]);
    session_destroy();
    $eliminadoExitosamente = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | ClimaInn</title>
    <link rel="icon" href="imagen/logopestaña.png" type="image/ico">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .pagination {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}
.pagination a {
    margin: 0 10px;
    padding: 10px;
    border: 1px solid #ccc;
    text-decoration: none;
    color: #333;
}
.pagination a:hover,
.pagination a.active {
    background-color: #007bff;
    color: #fff;
}
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            background-color: #007BFF;
            color: #fff;
            cursor: pointer;
            transition: background 300ms;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .button-container {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1;
            align-items: center;
            justify-content: center;
        }
        .modal-content {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }
        /* Estilos para el segundo modal */
        #successModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 2; 
            align-items: center;
            justify-content: center;
        }
        #successModal .modal-content {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 400px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Bienvenido, <?php echo $_SESSION['nombre_usuario']; ?></h1>
    <div class="button-container">
    <a href="logout.php" class="btn">Cerrar Sesión</a>
    <button onclick="showModal()" class="btn btn-danger">Eliminar Cuenta</button>
</div>

<div class="stats-container">
    <div class="gauge">
        <canvas id="temperatureChart"></canvas>
        <br>
        <div>Temperatura actual: <?php echo $lastTemp; ?>°C</div>
        <div>Min: <?php echo $minTemp; ?>°C</div>
        <div>Max: <?php echo $maxTemp; ?>°C</div>
        <div>Promedio: <?php echo number_format($avgTemp, 2); ?>°C</div>
    </div>
    <div class="gauge">
        <canvas id="humidityChart"></canvas>
        <br>
        <div>Humedad actual: <?php echo $lastHum; ?>%</div>
        <div>Min: <?php echo $minHum; ?>%</div>
        <div>Max: <?php echo $maxHum; ?>%</div>
        <div>Promedio: <?php echo number_format($avgHum, 2); ?>%</div>
    </div>
    <div class="gauge">
        <canvas id="luminosidadChart"></canvas>
        <br>
        <div>Luminosidad actual: <?php echo $lastLuminosidad; ?> %</div>
        <div>Min: <?php echo $minLuminosidad; ?> %</div>
        <div>Max: <?php echo $maxLuminosidad; ?> %</div>
        <div>Promedio: <?php echo number_format($avgLuminosidad, 2); ?> %</div>
    </div>
    <div class="gauge">
        <canvas id="presionChart"></canvas>
        <br>
        <div>Presión actual: <?php echo $lastPresion; ?> hPa</div>
        <div>Min: <?php echo $minPresion; ?> hPa</div>
        <div>Max: <?php echo $maxPresion; ?> hPa</div>
        <div>Promedio: <?php echo number_format($avgPresion, 2); ?> hPa</div>
    </div>
</div>
        <table>
            <thead>
                <tr>
                <th>Fecha y Hora</th>
                <th>Humedad</th>
                <th>Temperatura</th>
                <th>Luminosidad</th>
                <th>Presión Atmosférica</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $dato): ?>
                <tr>
                    <td><?php echo $dato['FechaHora']; ?></td>
                    <td><?php echo $dato['Humedad']; ?>%</td>
                    <td><?php echo $dato['Temperatura']; ?>°C</td>
                    <td><?php echo $dato['Luminosidad']; ?> %</td>
                    <td><?php echo $dato['PresionAtmosferica']; ?> hPa</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
    <a href="?page=1">Primera</a>
    <a href="?page=<?php echo max(1, $paginaActual - 1); ?>">Anterior</a>
    <span class="active">Página <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?></span>
    <a href="?page=<?php echo min($totalPaginas, $paginaActual + 1); ?>">Siguiente</a>
    <a href="?page=<?php echo $totalPaginas; ?>">Última</a>
</div>
        <div>
    <h2>Selecciona una fecha</h2>
    <form action="dashboard.php" method="post">
        <label for="selected_date">Fecha:</label>
        <input type="date" id="selected_date" name="selected_date">
        <input type="submit" value="Ver registros">
    </form>
</div>
<button id="descargarPDF" onclick="descargarPDF()">Descargar Reporte PDF</button>
    </div>
    <!-- Modal de confirmación -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <p>Estás a punto de eliminar tu cuenta. Todos tus datos se perderán y esta acción no se puede deshacer. ¿Estás seguro de que quieres continuar?</p>
            <div>
                <form method="POST">
                    <button type="submit" name="eliminarCuenta" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn" onclick="closeModal()">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
 <!-- Modal de éxito en la eliminación -->
 <div id="successModal" class="modal">
        <div class="modal-content">
            <p>Cuenta eliminada correctamente.</p>
            <div>
                <a href="login.php" class="btn">Volver al inicio</a>
            </div>
        </div>
    </div>
    <script>
        function showModal() {
            document.getElementById('confirmModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }
        <?php if($eliminadoExitosamente): ?>
            document.getElementById('successModal').style.display = 'flex';
        <?php endif; ?>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    var ctxTemperature = document.getElementById('temperatureChart').getContext('2d');
    var temperatureChart = new Chart(ctxTemperature, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_reverse(array_column($datos, 'FechaHora'))); ?>,
            datasets: [{
                label: 'Temperatura',
                data: <?php echo json_encode(array_reverse(array_column($datos, 'Temperatura'))); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255,99,132,1)',
                borderWidth: 1
            }]
        },
    });
    var ctxHumidity = document.getElementById('humidityChart').getContext('2d');
    var humidityChart = new Chart(ctxHumidity, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_reverse(array_column($datos, 'FechaHora'))); ?>,
            datasets: [{
                label: 'Humedad',
                data: <?php echo json_encode(array_reverse(array_column($datos, 'Humedad'))); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
    });
});
        var ctxLuminosidad = document.getElementById('luminosidadChart').getContext('2d');
        var luminosidadChart = new Chart(ctxLuminosidad, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_reverse(array_column($datos, 'FechaHora'))); ?>,
                datasets: [{
                    label: 'Luminosidad',
                    data: <?php echo json_encode(array_reverse(array_column($datos, 'Luminosidad'))); ?>,
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 1
                }]
            },
        });
        var ctxPresion = document.getElementById('presionChart').getContext('2d');
        var presionChart = new Chart(ctxPresion, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_reverse(array_column($datos, 'FechaHora'))); ?>,
                datasets: [{
                    label: 'Presión Atmosférica',
                    data: <?php echo json_encode(array_reverse(array_column($datos, 'PresionAtmosferica'))); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
        });
        function descargarPDF() {
  window.location.href = 'generar_reporte.php';
}
</script>
</body>
</html>
