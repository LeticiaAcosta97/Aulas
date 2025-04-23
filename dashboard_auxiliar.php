<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'auxiliar') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Auxiliar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background: #234080;
            min-height: 100vh;
        }
        .modulo-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 2.5rem 2rem;
        }
        .icono-auxiliar {
            font-size: 4rem;
            color: #1976d2;
        }
        .btn-danger {
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Encabezado de bienvenida y botón cerrar sesión -->
        <div class="mb-4">
            <h1 class="text-white mb-3">
                Bienvenido, <?php echo strtoupper(htmlspecialchars($_SESSION['nombre'])); ?> (AUXILIAR)
            </h1>
            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card modulo-card text-center mx-auto">
                    <div class="card-body">
                        <i class="bi bi-door-open icono-auxiliar mb-3"></i>
                        <h3 class="card-title mb-2">Módulo de Auxiliar</h3>
                        <p class="card-text">Accede a la asignación de aulas, consulta de aulas disponibles y equipos.</p>
                        <a href="asignacion_aulas.php" class="btn btn-primary mt-2">Ingresar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>