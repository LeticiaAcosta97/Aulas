<?php
include("config.php");

if (!isset($_GET['aula_id'])) {
    die("ID de aula no proporcionado");
}

$aula_id = intval($_GET['aula_id']);

// Obtener equipos asignados y disponibles
$query = "SELECT 
    e.*,
    CASE 
        WHEN ae2.aula_id IS NOT NULL THEN 
            CASE 
                WHEN ae2.aula_id = ? THEN 'Asignado'
                ELSE CONCAT('Asignado al Aula ', (SELECT numero FROM aulas WHERE id = ae2.aula_id LIMIT 1))
            END
        ELSE 'Sin Asignar'
    END as estado,
    ae.id as asignacion_id
    FROM equipos e
    LEFT JOIN aulas_equipos ae ON e.id = ae.equipo_id AND ae.aula_id = ?
    LEFT JOIN aulas_equipos ae2 ON e.id = ae2.equipo_id
    GROUP BY e.id, e.tipo, e.descripcion, e.marca, e.modelo, e.nro_serie, 
             e.fecha_instalacion, e.periodo_mantenimiento, e.nro_factura, 
             e.observaciones, ae.id, ae2.aula_id
    ORDER BY 
        CASE 
            WHEN ae2.aula_id = ? THEN 1
            WHEN ae2.aula_id IS NOT NULL THEN 2
            ELSE 3
        END,
        e.descripcion";

$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $aula_id, $aula_id, $aula_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Mostrar primero los equipos asignados -->
<div class="mb-4">
    <h6>Equipos Asignados</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Descripción</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    if ($row['estado'] == 'Asignado') { ?>
                        <tr>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td><?php echo $row['marca']; ?></td>
                            <td><?php echo $row['modelo']; ?></td>
                            <td><span class="badge bg-success">Asignado</span></td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="eliminarEquipo(<?php echo $aula_id; ?>, <?php echo $row['id']; ?>)">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Sección para asignar nuevos equipos -->
<div class="mb-4">
    <h6>Asignar Nuevo Equipo</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Descripción</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    if (strpos($row['estado'], 'Asignado al Aula') === 0 || $row['estado'] == 'Sin Asignar') { ?>
                        <tr>
                            <td><?php echo $row['descripcion']; ?></td>
                            <td><?php echo $row['marca']; ?></td>
                            <td><?php echo $row['modelo']; ?></td>
                            <td>
                                <?php if ($row['estado'] == 'Sin Asignar'): ?>
                                    <span class="badge bg-warning">Sin Asignar</span>
                                <?php else: ?>
                                    <span class="badge bg-info"><?php echo $row['estado']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['estado'] == 'Sin Asignar'): ?>
                                    <button class="btn btn-success btn-sm" onclick="guardarAsignacion(<?php echo $aula_id; ?>, <?php echo $row['id']; ?>)">
                                        <i class="bi bi-plus-circle"></i> Asignar
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="bi bi-lock"></i> No disponible
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>
</div>
