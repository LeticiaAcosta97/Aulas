<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Aulas</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Agregar Bootstrap para asegurar estilos base -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <img src="img/logo.png" alt="Logo">
        <h2>Bienvenido</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="procesar_login.php" method="POST">
            <div class="input-group">
                <input type="text" name="usuario" id="usuario" required>
                <label for="usuario">Usuario</label>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" id="password" required>
                <label for="password">Contraseña</label>
            </div>

            <button type="submit">Iniciar Sesión</button>
        </form>

        <div class="extra-buttons">
            <a href="recuperar_password.php">¿Olvidaste tu contraseña?</a>
            <a href="registro.php">Crear cuenta nueva</a>
        </div>
    </div>
</body>
</html>
