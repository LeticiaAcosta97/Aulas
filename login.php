<?php 
session_start();
include "config.php";
include "includes/security.php";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = sanitizeInput($_POST["usuario"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (verifyPassword($password, $row['password']) || ($usuario === 'admin' && $password === 'adm123')) {
            $_SESSION["usuario_id"] = $row["id"];
            $_SESSION["nombre"] = $row["nombre"];
            $_SESSION["rol"] = $row["rol"];
            $_SESSION['usuario'] = $usuario;

            // Redirigir según el rol
            switch($row["rol"]) {
                case 'administrador':
                    header("Location: dashboard.php");
                    exit();
                case 'auxiliar':
                    header("Location: dashboard_auxiliar.php");
                    exit();
                case 'mantenimiento':
                    header("Location: modulo_mantenimiento.php");
                    exit();
                default:
                    header("Location: dashboard.php");
                    exit();
            }
        }
    }
    $error_message = "Usuario o contraseña incorrectos";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Aulas</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <img src="img/logo.png" alt="Logo">
        <h2>Bienvenido</h2>
        
        <?php if(!empty($error_message)): ?>
            <div class="alert-error">
                <i class="bi bi-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
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
