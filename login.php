<?php
include 'conexion.php';

$mensajeError = "";
$inicioExitoso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE NombreDeUsuario = ? AND Contrasena = ?");
    $stmt->execute([$nombre_usuario, $contrasena]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        session_start();
        $_SESSION['idUsuario'] = $usuario['IDUsuario'];
        $_SESSION['nombre_usuario'] = $usuario['NombreDeUsuario'];
        $_SESSION['estacion'] = $usuario['Contrasena'];

        $inicioExitoso = true;
    } else {
        $mensajeError = "El nombre de usuario o contraseña no son válidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ClimaInn</title>
    <link rel="icon" href="imagen/logopestaña.png" type="image/ico">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header>
<div class="header-content">
    <div class="logo">
        <h1>Clima<b>Inn</b></h1>
    </div>
    <div class="menu" id="show-menu">
        <nav>
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="producto.php">Producto</a></li>
                <li><a href="login.php">Iniciar Sesion</a></li>
                <li><a href="registro.php">Registrarse</a></li>
            </ul>
        </nav>
    </div>
</div>
</header>
    <div class="container">
        <h1>Iniciar Sesion</h1>
        <form action="login.php" method="post">
            <input type="text" name="nombre_usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <?php if ($mensajeError): ?>
                <span style="color: red;"><?php echo $mensajeError; ?></span>
            <?php endif; ?>
            <button type="submit" class="ingresar-button">Ingresar</button>
        </form>
        <?php if ($inicioExitoso): ?>
        <div id="modalInicioExitoso" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
            <div style="padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
                Inicio exitoso!
            </div>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('modalInicioExitoso').style.display = 'none';
                window.location.href = 'dashboard.php';
            }, 1000);
        </script>
        <?php endif; ?>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
