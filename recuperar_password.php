<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Recuperar Contraseña</h2>
        <form action="enviar_recuperacion.php" method="POST">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" required>

            <button type="submit">Enviar enlace de recuperación</button>
        </form>
        <a href="login.php">Volver al inicio de sesión</a>
    </div>
</body>
</html>
