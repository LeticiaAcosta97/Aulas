<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'mantenimiento') {
    header("Location: acceso_denegado.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo de Mantenimiento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background: #234080;
        }
        .card-modulo {
            transition: box-shadow 0.2s;
            cursor: pointer;
        }
        .card-modulo:hover {
            box-shadow: 0 0 20px #1976d2;
        }
        .icono-mantenimiento {
            font-size: 4rem;
            color: #1976d2;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Encabezado de bienvenida y botón cerrar sesión -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-white fw-bold">Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?></h1>
            <a href="logout.php" class="btn btn-danger btn-lg">Cerrar Sesión</a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-modulo text-center" onclick="window.location.href='modulo_mantenimiento/index.php'">
                    <div class="card-body">
                        <i class="bi bi-tools icono-mantenimiento mb-3"></i>
                        <h3 class="card-title mb-2">Módulo de Mantenimiento</h3>
                        <p class="card-text">Accede a la gestión y observaciones de mantenimiento.</p>
                        <a href="dashboard_mantenimiento.php" class="btn btn-primary mt-2">Ingresar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>