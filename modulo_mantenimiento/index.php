<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'mantenimiento') {
    header("Location: ../acceso_denegado.php");
    exit();
}
include("../config.php");

// Consulta para obtener aulas y sus equipos
$sql = "SELECT a.id as aula_id, a.numero AS aula_numero, a.capacidad, 
               e.id as equipo_id, e.nombre as equipo_nombre, e.marca, e.modelo, e.nro_serie
        FROM aulas a
        LEFT JOIN aulas_equipos ae ON a.id = ae.aula_id
        LEFT JOIN equipos e ON ae.equipo_id = e.id
        ORDER BY a.numero, e.nombre";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Mantenimiento</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <!-- Menú principal con icono -->
        <div class="mb-4">
            <a href="../modulo_mantenimiento.php" class="btn btn-lg btn-primary d-flex align-items-center" style="width: fit-content;">
                <i class="bi bi-house-door-fill me-2" style="font-size: 1.5rem;"></i>
                Menú Principal
            </a>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Panel de Mantenimiento</h2>
        </div>
        <div class="mb-4">
            <a href="../registrar_observacion.php" class="btn btn-primary">Registrar Observación</a>
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong>Aulas y Equipos</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Número de Aula</th>
                            <th>Capacidad</th>
                            <th>Equipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>N° Patrimonio</th>
                            <th>Historial Mantenimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $last_aula = null;
                        while($row = $result->fetch_assoc()):
                            if (!$row['equipo_id']) continue; // skip if no equipo
                        ?>
                        <tr>
                            <td><?= ($last_aula !== $row['aula_numero']) ? htmlspecialchars($row['aula_numero']) : '' ?></td>
                            <td><?= ($last_aula !== $row['aula_numero']) ? htmlspecialchars($row['capacidad']) . ' alumnos' : '' ?></td>
                            <td><?= htmlspecialchars($row['equipo_nombre']) ?></td>
                            <td><?= htmlspecialchars($row['marca']) ?></td>
                            <td><?= htmlspecialchars($row['modelo']) ?></td>
                            <td><?= htmlspecialchars($row['nro_serie']) ?></td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#historialModal<?= $row['equipo_id'] ?>">
                                    Ver Historial
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="historialModal<?= $row['equipo_id'] ?>" tabindex="-1" aria-labelledby="historialModalLabel<?= $row['equipo_id'] ?>" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="historialModalLabel<?= $row['equipo_id'] ?>">Historial de Mantenimiento</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                      </div>
                                      <div class="modal-body">
                                        <?php
                                        $historial_sql = "SELECT fecha_mantenimiento, observaciones FROM historial_mantenimiento WHERE equipo_id = " . intval($row['equipo_id']) . " ORDER BY fecha_mantenimiento DESC";
                                        $historial_result = $conn->query($historial_sql);
                                        if ($historial_result && $historial_result->num_rows > 0) {
                                            while($hist = $historial_result->fetch_assoc()) {
                                                echo "<strong>Fecha:</strong> " . htmlspecialchars($hist['fecha_mantenimiento']) . "<br>";
                                                echo "<strong>Observación:</strong> " . nl2br(htmlspecialchars($hist['observaciones'])) . "<hr>";
                                            }
                                        } else {
                                            echo "<span class='text-muted'>Sin historial</span>";
                                        }
                                        ?>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </td>
                        </tr>
                        <?php $last_aula = $row['aula_numero']; endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>