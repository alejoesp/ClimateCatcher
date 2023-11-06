<?php
include 'conexion.php';

$mensaje_error = ''; // Inicializar la variable aquí

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $contraseña = $_POST['contraseña'];
    
    $stmt = $pdo->prepare("SELECT * FROM Administradores WHERE Nombre = ? AND Contraseña = ?");
    $stmt->execute([$nombre, $contraseña]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        session_start();
        $_SESSION['IDAdmin'] = $admin['IDAdmin'];
        header("Location: dashboard_admin.php"); // Redirigir al panel de administrador
        exit;
    } else {
        $mensaje_error = "Nombre o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="css/login_admin.css">
</head>
<body>
<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a href="inicio.html">ClimaInn</a>
        </div>
        <ul class="navbar-menu">
            <li><a href="inicio.html">Inicio</a></li>
            <!-- Puedes agregar más elementos de navegación aquí -->
        </ul>
    </div>
</nav>
<form action="login_admin.php" method="post">
    <?php if($mensaje_error): ?>
    <div class="error-msg">
        <?php echo $mensaje_error; ?>
    </div>
    <?php endif; ?>
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" required>
    <br>
    <label for="contraseña">Contraseña:</label>
    <input type="password" name="contraseña" id="contraseña" required>
    <br>
    <input type="submit" value="Ingresar">
</form>
</body>
</html>
