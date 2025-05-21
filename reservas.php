<?php
session_start();
include "config.php";

// Obtener lista de espacios
$espacios = $conn->query("SELECT * FROM espacios ORDER BY tipo, nombre");

// Procesar reserva
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $espacio_id = $_POST['espacio_id'];
    $responsable = $_SESSION['nombre'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $actividad = $_POST['actividad'];

    $stmt = $conn->prepare("INSERT INTO reservas (espacio_id, responsable, fecha, hora_inicio, hora_fin, actividad) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $espacio_id, $responsable, $fecha, $hora_inicio, $hora_fin, $actividad);
    $stmt->execute();
    $stmt->close();
    $mensaje = "Reserva realizada correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservas de Salas/Auditorios/Laboratorios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            min-height: 100vh;
        }
        .main-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 30px;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        h2 {
            color: #1e3c72;
        }
        .btn-primary {
            background-color: #1e3c72;
            border: none;
        }
        .btn-primary:hover {
            background-color: #16305a;
        }
        .form-label {
            color: #1e3c72;
            font-weight: 500;
        }
        .alert-success {
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 main-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-center w-100">Reservar Sala, Auditorio o Laboratorio</h2>
                <a href="dashboard.php" class="btn btn-primary ms-3">Volver al Dashboard</a>
            </div>
            <?php if (!empty($mensaje)): ?>
                <div class="alert alert-success text-center"><?= $mensaje ?></div>
            <?php endif; ?>
            <form method="POST" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="espacio_id" class="form-label">Espacio</label>
                    <select name="espacio_id" id="espacio_id" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <?php
                        // Volver a ejecutar la consulta porque fetch_assoc agota el resultado anterior
                        $espacios = $conn->query("SELECT * FROM espacios ORDER BY tipo, nombre");
                        while($espacio = $espacios->fetch_assoc()): ?>
                            <option value="<?= $espacio['id'] ?>">
                                <?= $espacio['nombre'] ?> (<?= $espacio['tipo'] ?>, Capacidad: <?= $espacio['capacidad'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="hora_inicio" class="form-label">Hora Inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="hora_fin" class="form-label">Hora Fin</label>
                    <input type="time" name="hora_fin" id="hora_fin" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="actividad" class="form-label">Actividad</label>
                    <input type="text" name="actividad" id="actividad" class="form-control" required>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary w-50">Reservar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>