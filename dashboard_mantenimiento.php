<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'mantenimiento') {
    header("Location: acceso_denegado.php");
    exit();
}
include("config.php");

// Procesar registro de observación
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['equipo_id'], $_POST['fecha_mantenimiento'], $_POST['observaciones'])) {
    $equipo_id = intval($_POST['equipo_id']);
    $fecha = $_POST['fecha_mantenimiento'];
    $observaciones = trim($_POST['observaciones']);
    if ($equipo_id && $fecha && $observaciones) {
        $stmt = $conn->prepare("INSERT INTO historial_mantenimiento (equipo_id, fecha_mantenimiento, observaciones) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $equipo_id, $fecha, $observaciones);
        $stmt->execute();
        $stmt->close();
        // Redirigir para evitar reenvío del formulario
        header("Location: dashboard_mantenimiento.php?ok=1");
        exit();
    }
}

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
    <div class="container-fluid mt-5">
        <!-- Menú principal con icono -->
        <div class="mb-4">
            <a href="modulo_mantenimiento.php" class="btn btn-lg btn-primary d-flex align-items-center" style="width: fit-content;">
                <i class="bi bi-house-door-fill me-2" style="font-size: 1.5rem;"></i>
                Menú Principal
            </a>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Panel de Mantenimiento</h2>
        </div>
        <!-- Registrar Observación button REMOVED from here -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong>Aulas y Equipos</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Aula</th>
                            <th>Tipo</th>
                            <th>Descripción</th>
                            <th>Estado Asignación</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>N° Serie/Patrimonio</th>
                            <th>Fecha Instalación</th>
                            <th>Último Mantenimiento</th>
                            <th>Observaciones</th>
                            <th>Período Mant.</th>
                            <th>Historial Mantenimiento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ajusta tu consulta SQL para traer estos campos
                        $sql = "SELECT 
                                    a.numero AS aula_numero,
                                    e.tipo,
                                    e.nombre AS descripcion,
                                    CASE 
                                        WHEN ae.aula_id IS NOT NULL THEN CONCAT('Asignado al Aula ', a.numero)
                                        ELSE 'Sin Asignar'
                                    END AS estado_asignacion,
                                    e.marca,
                                    e.modelo,
                                    e.nro_serie,
                                    e.fecha_instalacion,
                                    (SELECT fecha_mantenimiento FROM historial_mantenimiento WHERE equipo_id = e.id ORDER BY fecha_mantenimiento DESC LIMIT 1) AS ultimo_mantenimiento,
                                    (SELECT observaciones FROM historial_mantenimiento WHERE equipo_id = e.id ORDER BY fecha_mantenimiento DESC LIMIT 1) AS observaciones,
                                    e.periodo_mantenimiento,
                                    e.id AS equipo_id
                                FROM equipos e
                                LEFT JOIN aulas_equipos ae ON e.id = ae.equipo_id
                                LEFT JOIN aulas a ON ae.aula_id = a.id
                                WHERE ae.aula_id IS NOT NULL
                                ORDER BY a.numero, e.nombre";
                        $result = $conn->query($sql);
                        
                        while($row = $result->fetch_assoc()):
                            // Colorear Último Mantenimiento
                            $color = '';
                            $texto = '';
                            if ($row['ultimo_mantenimiento']) {
                                $fecha_ultimo = $row['ultimo_mantenimiento'];
                                $periodo = intval($row['periodo_mantenimiento']);
                                $fecha_prox = date('Y-m-d', strtotime($fecha_ultimo . " +$periodo days"));
                                $hoy = date('Y-m-d');
                                if ($fecha_prox < $hoy) {
                                    $color = 'bg-danger text-white';
                                    $texto = 'Mantenimiento Vencido';
                                } elseif ($fecha_prox == $hoy || (strtotime($fecha_prox) - strtotime($hoy)) <= (7*24*60*60)) {
                                    $color = 'bg-warning text-dark';
                                    $texto = 'Próximo a Vencer';
                                } else {
                                    $color = 'bg-success text-white';
                                    $texto = 'Al día';
                                }
                            }
                        ?>
                        <tr>
                            <td><?= $row['aula_numero'] ? 'Aula ' . htmlspecialchars($row['aula_numero'] ?? '') : 'Sin asignar' ?></td>
                            <td><?= htmlspecialchars($row['tipo'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['descripcion'] ?? '') ?></td>
                            <td>
                                <?php if (strpos($row['estado_asignacion'] ?? '', 'Asignado') !== false): ?>
                                    <span class="badge bg-success"><?= htmlspecialchars($row['estado_asignacion'] ?? '') ?></span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><?= htmlspecialchars($row['estado_asignacion'] ?? '') ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['marca'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['modelo'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['nro_serie'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['fecha_instalacion'] ?? '') ?></td>
                            <td class="<?= $color ?>">
                                <?= htmlspecialchars($row['ultimo_mantenimiento'] ?? '') ?>
                                <br>
                                <?= $texto ?>
                            </td>
                            <td><?= htmlspecialchars($row['observaciones'] ?? '') ?></td>
                            <td><?= htmlspecialchars($row['periodo_mantenimiento'] ?? '') ?> días</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#historialModal<?= $row['equipo_id'] ?>">
                                    Ver Historial
                                </button>
                                <button class="btn btn-success btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#registrarModal<?= $row['equipo_id'] ?>">
                                    Registrar Observación
                                </button>
                                <!-- Modal Historial -->
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
                                                echo "<div><strong>Fecha:</strong> " . htmlspecialchars($hist['fecha_mantenimiento'] ?? '') . "<br>";
                                                echo "<strong>Obs:</strong> " . nl2br(htmlspecialchars($hist['observaciones'] ?? '')) . "</div><hr>";
                                            }
                                        } else {
                                            echo "<span class='text-muted'>Sin historial</span>";
                                        }
                                        ?>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <!-- Modal Registrar Observación -->
                                <div class="modal fade" id="registrarModal<?= $row['equipo_id'] ?>" tabindex="-1" aria-labelledby="registrarModalLabel<?= $row['equipo_id'] ?>" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <form method="POST" action="">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="registrarModalLabel<?= $row['equipo_id'] ?>">Registrar Mantenimiento</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                          <input type="hidden" name="equipo_id" value="<?= $row['equipo_id'] ?>">
                                          <div class="mb-3">
                                            <label class="form-label fw-bold">Equipo seleccionado:</label>
                                            <div>
                                              <?= htmlspecialchars($row['descripcion'] ?? $row['equipo_nombre'] ?? '') ?> (<?= htmlspecialchars($row['marca'] ?? '') ?> <?= htmlspecialchars($row['modelo'] ?? '') ?> - Patrimonio: <?= htmlspecialchars($row['nro_serie'] ?? '') ?>)
                                            </div>
                                          </div>
                                          <div class="mb-3">
                                            <label for="fecha_mantenimiento_<?= $row['equipo_id'] ?>" class="form-label">Fecha de Mantenimiento</label>
                                            <input type="date" class="form-control" id="fecha_mantenimiento_<?= $row['equipo_id'] ?>" name="fecha_mantenimiento" value="<?= date('Y-m-d') ?>" required>
                                          </div>
                                          <div class="mb-3">
                                            <label for="observaciones_<?= $row['equipo_id'] ?>" class="form-label">Observaciones</label>
                                            <textarea class="form-control" id="observaciones_<?= $row['equipo_id'] ?>" name="observaciones" rows="3" required></textarea>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                          <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>