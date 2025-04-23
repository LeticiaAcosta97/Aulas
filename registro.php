<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            margin: 20px;
        }
        .form-control, .form-select {
            margin-bottom: 1rem;
        }
        .btn-registrar {
            background-color: #dc3545;
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            margin-top: 1rem;
        }
        .btn-registrar:hover {
            background-color: #bb2d3b;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Crear Cuenta</h2>
        <form action="procesar_registro.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nombre de Usuario:</label>
                        <input type="text" class="form-control" name="usuario" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Apellido:</label>
                <input type="text" class="form-control" name="apellido" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo Electrónico:</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol:</label>
                <select class="form-select" name="rol" required>
                    <option value="">Seleccione un rol</option>
                    <option value="administrador">Administrador</option>
                    <option value="auxiliar">Auxiliar</option>
                    <option value="mantenimiento">Mantenimiento</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Confirmar Contraseña:</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-registrar">Registrarse</button>
            
            <div class="text-center mt-3">
                <a href="login.php" class="text-decoration-none">¿Ya tienes cuenta? Inicia sesión</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

