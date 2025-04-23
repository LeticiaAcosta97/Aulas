<?php
include("auth.php");
require_role(['administrador', 'auxiliar']);
include("config.php");

if (isset($_GET['mensaje'])) {
    $mensaje_alerta = htmlspecialchars(urldecode($_GET['mensaje']));
    $tipo_alerta = isset($_GET['tipo']) ? $_GET['tipo'] : 'warning';
}

$query = "SELECT a.id, a.numero, a.capacidad,
          GROUP_CONCAT(DISTINCT cur.turno ORDER BY cur.turno) as turnos_asignados,
          GROUP_CONCAT(
              DISTINCT CONCAT(
                  'Carrera: ', car.nombre, 
                  '<br>Curso: ', cur.nombre,
                  '<br>Turno: ', cur.turno
              ) ORDER BY cur.turno
              SEPARATOR '<hr>'
          ) as detalles_asignacion
          FROM aulas a
          LEFT JOIN asignaciones asig ON a.id = asig.aula_id
          LEFT JOIN cursos cur ON asig.curso_id = cur.id
          LEFT JOIN carreras car ON cur.carrera_id = car.id";

// --- CORRECCIÓN DE FILTROS ---
$where = [];
if (isset($_GET['estado']) && $_GET['estado'] !== '') {
    $estado = $_GET['estado'];
    $where[] = "(SELECT COUNT(*) FROM asignaciones WHERE aula_id = a.id) = $estado";
}
if (isset($_GET['capacidad']) && !empty($_GET['capacidad'])) {
    $capacidad = intval($_GET['capacidad']);
    $where[] = "a.capacidad >= $capacidad";
}
if (count($where) > 0) {
    $query .= " WHERE " . implode(' AND ', $where);
}
// --- FIN CORRECCIÓN DE FILTROS ---

$query .= " GROUP BY a.id ORDER BY a.numero";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignación de Aulas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>

<!-- MODAL PARA MOSTRAR EQUIPOS -->
<div class="modal fade" id="modalEquipos" tabindex="-1" aria-labelledby="modalEquiposLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Equipos en el Aula</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="equiposLista">
            </div>
        </div>
    </div>
</div>

<!-- Place this right after the opening <body> tag -->
<!-- Toast containers - Remove any duplicate toast containers -->
<div class="toast-container position-fixed top-50 start-50 translate-middle">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">Éxito</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Equipos actualizados correctamente
        </div>
    </div>
    <div id="deleteToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <strong class="me-auto">Eliminación</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Equipo eliminado correctamente
        </div>
    </div>
</div>

<!-- Mantener solo un modal -->
<div class="modal fade" id="modalEquipos" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Equipos en el Aula</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="equiposLista">
            </div>
        </div>
    </div>
</div>

<!-- Mantener solo un conjunto de toasts -->
<div class="toast-container position-fixed top-50 start-50 translate-middle">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">Éxito</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Equipos actualizados correctamente
        </div>
    </div>
    <div id="deleteToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <strong class="me-auto">Eliminación</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Equipo eliminado correctamente
        </div>
    </div>
</div>

<!-- Corregir la estructura del script -->
<script>
    function verEquipos(aula_id) {
        fetch('obtener_equipos.php?aula_id=' + aula_id)
            .then(response => response.text())
            .then(data => {
                document.getElementById('equiposLista').innerHTML = data;
                var modal = new bootstrap.Modal(document.getElementById('modalEquipos'));
                modal.show();
            });
    }

    function guardarAsignacion(aula_id, equipo_id) {
        fetch('guardar_asignacion_equipo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `equipo_id=${equipo_id}&aula_id=${aula_id}&cantidad=1`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const modalEquipos = bootstrap.Modal.getInstance(document.getElementById('modalEquipos'));
                modalEquipos.hide();
                const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                successToast.show();
                setTimeout(() => {
                    verEquipos(aula_id);
                }, 500);
            } else {
                alert(data.message || 'Error al asignar el equipo');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    }

    function eliminarEquipo(aula_id, equipo_id) {
        if (confirm('¿Está seguro de eliminar este equipo del aula?')) {
            fetch('eliminar_asignacion_equipo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `equipo_id=${equipo_id}&aula_id=${aula_id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const deleteToast = new bootstrap.Toast(document.getElementById('deleteToast'));
                    deleteToast.show();
                    verEquipos(aula_id);
                } else {
                    alert(data.message || 'Error al eliminar el equipo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        }
    }
</script>

<div class="toast-container position-fixed top-50 start-50 translate-middle">
    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">Éxito</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Equipos actualizados correctamente
        </div>
    </div>
</div>

<div class="container mt-5">
    <?php if (isset($mensaje_alerta)): ?>
        <div class="alert alert-<?php echo $tipo_alerta; ?> alert-dismissible fade show" role="alert">
            <?php echo $mensaje_alerta; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="dashboard.php" class="btn btn-primary me-2">
                <i class="bi bi-arrow-left-circle"></i> Menú Principal
            </a>
            <a href="reporte_asignaciones.php" class="btn btn-info">
                <i class="bi bi-file-text"></i> Ver Reporte de Asignaciones
            </a>
        </div>
        <h2>Asignación de Aulas</h2>
        <div style="width: 300px;"></div>
    </div>

    <!-- Agregar buscador -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3" method="GET">
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select class="form-select" name="estado">
                        <option value="">Todos</option>
                        <option value="0" <?php echo isset($_GET['estado']) && $_GET['estado'] === '0' ? 'selected' : ''; ?>>Sin asignar</option>
                        <option value="1" <?php echo isset($_GET['estado']) && $_GET['estado'] === '1' ? 'selected' : ''; ?>>Asignados</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Capacidad mínima</label>
                    <input type="number" class="form-control" name="capacidad" value="<?php echo isset($_GET['capacidad']) ? $_GET['capacidad'] : ''; ?>" placeholder="Ej: 30">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="asignacion_aulas.php" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de aulas -->
    <table class="table">
        <thead>
            <tr>
                <th>Número de Aula</th>
                <th>Capacidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['numero']; ?></td>
                <td><?php echo $row['capacidad']; ?> alumnos</td>
                <td>
                    <button class="btn btn-info btn-sm" onclick="verEquipos(<?php echo $row['id']; ?>)">Equipos</button>
                    <?php 
                    $turnos_asignados = !empty($row['turnos_asignados']) ? explode(',', $row['turnos_asignados']) : [];
                    $turnos_disponibles = array_diff(['Mañana', 'Tarde', 'Noche'], $turnos_asignados);
                    
                    if (!empty($turnos_disponibles)) {
                        ?>
                        <a href="asignar_curso.php?aula_id=<?php echo $row['id']; ?>&turnos_disponibles=<?php echo urlencode(json_encode($turnos_disponibles)); ?>" 
                           class="btn btn-success btn-sm">
                           <i class="bi bi-plus-circle"></i> Asignar 
                           (<?php echo implode(', ', $turnos_disponibles); ?>)
                        </a>
                        <?php
                    }
                    
                    if (!empty($row['detalles_asignacion'])) { 
                        ?>
                        <div class="mt-2 alert alert-info">
                            <strong><i class="bi bi-info-circle"></i> Asignaciones actuales:</strong><br>
                            <?php 
                            $query_asignaciones = "SELECT a.id, c.nombre as curso, c.turno, car.nombre as carrera 
                                                FROM asignaciones a
                                                JOIN cursos c ON a.curso_id = c.id
                                                JOIN carreras car ON c.carrera_id = car.id
                                                WHERE a.aula_id = " . $row['id'];
                            $asignaciones = $conn->query($query_asignaciones);
                            while($asig = $asignaciones->fetch_assoc()) {
                                echo "<div class='mb-2'>";
                                echo "Carrera: " . $asig['carrera'] . "<br>";
                                echo "Curso: " . $asig['curso'] . "<br>";
                                echo "Turno: " . $asig['turno'] . "<br>";
                                echo "<a href='modificar_asignacion.php?asignacion_id=" . $asig['id'] . "' class='btn btn-warning btn-sm me-2'>";
                                echo "<i class='bi bi-pencil'></i> Modificar</a>";
                                echo "<a href='eliminar_asignacion.php?asignacion_id=" . $asig['id'] . "' 
                                      class='btn btn-danger btn-sm'
                                      onclick='return confirm(\"¿Estás seguro de eliminar esta asignación?\");'>";
                                echo "<i class='bi bi-trash'></i> Eliminar</a>";
                                echo "</div><hr>";
                            }
                            ?>
                        </div>
                    <?php 
                    }
                    ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <!-- Eliminar esta línea redundante -->
    <!-- <a href="reporte_asignaciones.php" class="btn btn-info mt-3">Ver Reporte de Asignaciones</a> -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
