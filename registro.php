<?php
include 'conexion.php';
$mensajeError = "";
$registroExitoso = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = $_POST['nombre_completo'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $stmt = $pdo->prepare("SELECT CodigoEstacion FROM estaciones WHERE CodigoEstacion = ?");
    $stmt->execute([$contrasena]);
    $estacion = $stmt->fetch();
    if ($estacion) {
        try {
            $stmt = $pdo->prepare("INSERT INTO usuarios (NombreCompleto, NombreDeUsuario, Correo, Contrasena) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre_completo, $nombre_usuario, $correo, $contrasena]);

            $idUsuario = $pdo->lastInsertId();
            $stmt = $pdo->prepare("INSERT INTO estacionesusuarios (IDUsuario, CodigoEstacion) VALUES (?, ?)");
            $stmt->execute([$idUsuario, $contrasena]);

            $registroExitoso = true;
        } catch (PDOException $e) {
            // Detectar si el error es debido a la clave única
            if ($e->errorInfo[1] == 1062) {
                $mensajeError = "El usuario ya está registrado. ";
            } else {
                $mensajeError = "Ha ocurrido un error al registrar. Por favor, inténtalo de nuevo más tarde.";
            }
        }
    } else {
        $mensajeError = "El código de producto no es válido.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | ClimaInn</title>
    <link rel="icon" href="imagen/logopestaña.png" type="image/ico">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<!--Header - menu-->
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
        <h1>Registro de Usuario</h1>
        <form action="registro.php" method="post">
            <input type="text" name="nombre_completo" placeholder="Nombre Completo" required>
            <input type="text" name="nombre_usuario" placeholder="Nombre de Usuario" required>
            <input type="email" name="correo" placeholder="Correo" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <?php if ($mensajeError): ?>
                <span style="color: red;"><?php echo $mensajeError; ?></span>
            <?php endif; ?>
            <button type="submit" class="ingresar-button">Registrarse</button>
        </form>
        <?php if ($registroExitoso): ?>
        <div id="modalRegistroExitoso" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;">
            <div style="padding: 20px; background-color: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
                Registro exitoso!
            </div>
        </div>
        <script>
            setTimeout(function() {
                document.getElementById('modalRegistroExitoso').style.display = 'none';
                window.location.href = 'login.php';
            }, 2000);
        </script>
        <?php endif; ?>
    </div>
<div class="instruccion-codigo-producto">
    <p>Para registrarte y registrar tu ClimateCatcher, debes colocar el código de producto como contraseña que se encuentra en uno de los lados del disposiitivo.</p>
    <div class="imagen-codigo-producto">
        <img src="imagen/7.PNG" alt="Ejemplo del código de producto">
    </div>
</div>
    <script src="scripts.js"></script>
</body>
</html> 
