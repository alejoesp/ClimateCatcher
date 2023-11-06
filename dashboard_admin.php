<?php
include 'conexion.php';
session_start();

// Verificar si el usuario está logueado como administrador
if (!isset($_SESSION['IDAdmin'])) {
    header("Location: login_admin.php");
    exit;
}

// Obtener todos los usuarios
$sql_users = "SELECT * FROM usuarios";
$stmt = $pdo->prepare($sql_users);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - Dashboard</title>
    <link rel="stylesheet" href="css/dashboard_admin.css"> 
</head>
<body>
<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a href="inicio.html">ClimaInn</a>
        </div>
        <ul class="navbar-menu">
            <li><a href="dashboard_admin.php">Inicio</a></li>
        </ul>
    </div>
</nav>

<div class="dashboard-container">
    <div class="usuarios-section">
        <h2>Usuarios y sus Estaciones</h2>
        <?php if (!empty($users)): ?>
            <?php foreach($users as $user): ?>
                <div class="usuario-item">
                    <p>Nombre del Usuario: <?= htmlspecialchars($user["NombreDeUsuario"]); ?></p>
                    <p>Nombre Completo: <?= htmlspecialchars($user["NombreCompleto"]); ?></p>
                    
                    <?php 
                    $sql_stations = "SELECT e.NombreEstacion 
                                    FROM estaciones e 
                                    INNER JOIN estacionesusuarios eu ON e.CodigoEstacion = eu.CodigoEstacion 
                                    WHERE eu.IDUsuario = ?";
                    $stmt = $pdo->prepare($sql_stations);
                    $stmt->execute([$user["IDUsuario"]]);
                    $stations = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <p>Estacion a la que tiene acceso: 
                        <?php if (!empty($stations)): ?>
                            <?php foreach($stations as $station): ?>
                                <?= htmlspecialchars($station["NombreEstacion"]); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            Ninguna
                        <?php endif; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay usuarios registrados.</p>
        <?php endif; ?>
    </div>
    <div class="estaciones-section">
        <h2>Todas las Estaciones</h2>
        <?php 
        $sql_all_stations = "SELECT * FROM estaciones";
        $stmt = $pdo->prepare($sql_all_stations);
        $stmt->execute();
        $all_stations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <?php if (!empty($all_stations)): ?>
            <?php foreach($all_stations as $station): ?>
                <div class="estacion-item">
                    <p>Código: <?= htmlspecialchars($station['CodigoEstacion']); ?></p>
                    <p>Nombre de Estación: <?= htmlspecialchars($station['NombreEstacion']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay estaciones registradas.</p>
        <?php endif; ?>
        </div>
</div>

</body>
</html>
<?php
// Cerrar la conexión
unset($pdo);
?>