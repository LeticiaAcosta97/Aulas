<?php
include "includes/security.php";
checkSession();

// Verificar tiempo de inactividad (30 minutos)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_destroy();
    header("Location: login.php");
    exit();
}
$_SESSION['last_activity'] = time();

// Eliminar el session_start() redundante y simplificar la verificación del nombre
if (!isset($_SESSION["nombre"])) {
    $_SESSION["nombre"] = "Administrador"; // Si no hay nombre, usa un valor predeterminado
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .module-card {
            transition: transform 0.3s;
        }
        .module-card:hover {
            transform: translateY(-5px);
        }
        .module-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-center mb-4">Bienvenido, <?php echo htmlspecialchars($_SESSION["nombre"]); ?></h1>
                <div class="text-end">
                    <button onclick="location.href='logout.php'" class="btn btn-danger">Cerrar Sesión</button>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Módulo de Asignación de Aulas -->
            <div class="col-md-5">
                <a href="asignacion_aulas.php" class="text-decoration-none">
                    <div class="card module-card h-100 shadow">
                        <div class="card-body text-center p-4">
                            <!-- Cambiar la imagen por una más moderna -->
                            <img src="https://cdn-icons-png.flaticon.com/512/10015/10015999.png" alt="Aulas" class="img-fluid mb-3" style="max-width: 150px;">
                            <h3 class="card-title text-primary">Asignación de Aulas</h3>
                            <p class="card-text text-muted">Gestiona la asignación de aulas y sus equipos</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Módulo de Gestión de Inventario -->
            <div class="col-md-5">
                <a href="gestion_inventario.php" class="text-decoration-none">
                    <div class="card module-card h-100 shadow">
                        <div class="card-body text-center p-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/2897/2897785.png" alt="Inventario" class="img-fluid mb-3" style="max-width: 150px;">
                            <h3 class="card-title text-primary">Gestión de Inventario</h3>
                            <p class="card-text text-muted">Administra el inventario de equipos y recursos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

