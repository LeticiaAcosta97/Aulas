<?php
session_start();
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['mantenimiento', 'administrador'])) {
    header("Location: acceso_denegado.php");
    exit();
}
include("config.php");

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $equipo_id = intval($_POST['equipo_id']);
    $fecha = date('Y-m-d');
    $observaciones = trim($_POST['observaciones']);

    if ($equipo_id && $observaciones) {
        $stmt = $conn->prepare("INSERT INTO historial_mantenimiento (equipo_id, fecha_mantenimiento, observaciones) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $equipo_id, $fecha, $observaciones);
        if ($stmt->execute()) {
            $mensaje = "Observación registrada correctamente.";
        } else {
            $mensaje = "Error al registrar la observación.";
        }
        $stmt->close();
    } else {
        $mensaje = "Todos los campos son obligatorios.";
    }
}

// Obtener lista de equipos para seleccionar
$equipos = $conn->query("SELECT id, nombre, marca, modelo, nro_serie FROM equipos ORDER BY nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Observación de Mantenimiento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registrar Observación de Mantenimiento</h2>
        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="equipo_id" class="form-label">Equipo</label>
                <select name="equipo_id" id="equipo_id" class="form-select" required>
                    <option value="">Seleccione un equipo</option>
                    <?php while($eq = $equipos->fetch_assoc()): ?>
                        <option value="<?= $eq['id'] ?>">
                            <?= htmlspecialchars($eq['nombre']) ?> (<?= htmlspecialchars($eq['marca']) ?> <?= htmlspecialchars($eq['modelo']) ?> - Patrimonio: <?= htmlspecialchars($eq['nro_serie']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea name="observaciones" id="observaciones" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
            <a href="dashboard_mantenimiento.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html>