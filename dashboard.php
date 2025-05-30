<?php
session_start();
include "includes/security.php";

// Verificar que solo administradores puedan acceder
checkUserRole(['administrador']);

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
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            min-height: 100vh;
        }
        .card {
            background: white;
            border: none;
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-text {
            color: #666;
        }
        h1 {
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 8px 20px;
        }
        .btn-danger:hover {
            background-color: #bb2d3b;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1>Bienvenido, <?php echo strtoupper($_SESSION['nombre']); ?> (<?php echo strtoupper($_SESSION['rol']); ?>)</h1>
                <button class="btn btn-danger" onclick="location.href='logout.php'">Cerrar Sesión</button>
                <!-- Eliminar el bloque dashboard-options para evitar duplicados -->
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Módulo de Asignación de Aulas -->
            <div class="col-md-5">
                <a href="asignacion_aulas.php" class="text-decoration-none">
                    <div class="card module-card h-100 shadow">
                        <div class="card-body text-center p-4">
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

            <!-- NUEVO: Módulo de Gestión de Alumnos -->
            <div class="col-md-5">
                <a href="alumnos.php" class="text-decoration-none">
                    <div class="card module-card h-100 shadow">
                        <div class="card-body text-center p-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Alumnos" class="img-fluid mb-3" style="max-width: 150px;">
                            <h3 class="card-title text-primary">Gestión de Alumnos</h3>
                            <p class="card-text text-muted">Registrar y listar alumnos</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- NUEVO: Módulo de Gestión de Cursos -->
            <div class="col-md-5">
                <a href="cursos.php" class="text-decoration-none">
                    <div class="card module-card h-100 shadow">
                        <div class="card-body text-center p-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/2942/2942842.png" alt="Cursos" class="img-fluid mb-3" style="max-width: 150px;">
                            <h3 class="card-title text-primary">Gestión de Cursos</h3>
                            <p class="card-text text-muted">Ver cursos y alumnos matriculados</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- NUEVO: Módulo de Reservas de Salas/Auditorios/Laboratorios -->
            <div class="col-md-5">
                <a href="reservas.php" class="text-decoration-none">
                    <div class="card module-card h-100 shadow">
                        <div class="card-body text-center p-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/3062/3062634.png" alt="Reservas" class="img-fluid mb-3" style="max-width: 150px;">
                            <h3 class="card-title text-primary">Reservas de Salas/Auditorios/Laboratorios</h3>
                            <p class="card-text text-muted">Permite reservar espacios para actividades especiales y ver la capacidad de cada sala</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

