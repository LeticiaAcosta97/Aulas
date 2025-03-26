<?php
include("config.php"); // Conexión a la BD

// Update the query to include assignment details
// Modificar la consulta para quitar la referencia a asig.turno
// Modificar la consulta para usar la relación correcta entre cursos y carreras
// Modificar la consulta para incluir más detalles
$query = "SELECT a.id, a.numero, a.capacidad, 
          (SELECT COUNT(*) FROM asignaciones WHERE aula_id = a.id) AS asignada,
          CASE 
              WHEN asig.id IS NOT NULL THEN 
                  CONCAT('Carrera: ', car.nombre, 
                        '<br>Curso: ', cur.nombre,
                        '<br>Turno: ', cur.turno,
                        '<br>Alumnos matriculados: ', cur.alumnos_matriculados)
              ELSE ''
          END as detalles_asignacion
          FROM aulas a
          LEFT JOIN asignaciones asig ON a.id = asig.aula_id
          LEFT JOIN cursos cur ON asig.curso_id = cur.id
          LEFT JOIN carreras car ON cur.carrera_id = car.id
          WHERE a.numero BETWEEN 50 AND 100
          ORDER BY a.numero";

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
    <div class="modal-dialog modal-lg"> <!-- Cambiado a modal-lg para hacerlo más ancho -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Equipos en el Aula</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="equiposLista">
                Cargando equipos...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="agregarEquipo()">Agregar Equipo</button>
                <button type="button" class="btn btn-warning" id="btnModificar">Modificar</button>
                <button type="button" class="btn btn-success" id="btnGuardar" style="display:none;">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast de notificación -->
<!-- Reemplazar el toast actual con este -->
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
    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars(urldecode($_GET['mensaje'])); ?>
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
        <div style="width: 300px;"></div> <!-- Adjusted spacer width -->
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

    <!-- Modificar la consulta SQL -->
    <?php
    $query = "SELECT a.*, 
              CASE WHEN asg.id IS NOT NULL 
                   THEN CONCAT('Carrera: ', ca.nombre, '<br>Curso: ', c.nombre, '<br>Turno: ', c.turno)
                   ELSE NULL 
              END as detalles_asignacion,
              CASE WHEN asg.id IS NOT NULL THEN 1 ELSE 0 END as asignada
              FROM aulas a
              LEFT JOIN asignaciones asg ON a.id = asg.aula_id
              LEFT JOIN cursos c ON asg.curso_id = c.id
              LEFT JOIN carreras ca ON c.carrera_id = ca.id
              WHERE 1=1";

    if (isset($_GET['estado']) && $_GET['estado'] !== '') {
        $estado = $_GET['estado'];
        $query .= " AND (CASE WHEN asg.id IS NOT NULL THEN 1 ELSE 0 END) = $estado";
    }

    if (isset($_GET['capacidad']) && !empty($_GET['capacidad'])) {
        $capacidad = intval($_GET['capacidad']);
        $query .= " AND a.capacidad >= $capacidad";
    }

    $query .= " ORDER BY a.numero";
    $result = $conn->query($query);
    ?>

    <table class="table table-bordered">
        <thead class="table-dark">
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
                        <?php if ($row['asignada'] == 0) { ?>
                            <a href="asignar_curso.php?aula_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">Asignar</a>
                        <?php } else { ?>
                            <button class="btn btn-secondary btn-sm" disabled>Asignado</button>
                            <a href="modificar_asignacion.php?aula_id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Modificar</a>
                            <a href="eliminar_asignacion.php?aula_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta asignación?');">Eliminar</a>
                            <div class="mt-2 alert alert-info">
                                <strong>Asignación actual:</strong>
                                <?php echo $row['detalles_asignacion']; ?>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- Eliminar esta línea redundante -->
    <!-- <a href="reporte_asignaciones.php" class="btn btn-info mt-3">Ver Reporte de Asignaciones</a> -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function verEquipos(aula_id) {
    // Guardar el ID del aula actual
    window.aulaActualId = aula_id;
    
    fetch('obtener_equipos.php?aula_id=' + aula_id)
        .then(response => response.text())
        .then(data => {
            document.getElementById('equiposLista').innerHTML = data;
            var modal = new bootstrap.Modal(document.getElementById('modalEquipos'));
            modal.show();
        });
}

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("btnModificar").addEventListener("click", function() {
        document.querySelectorAll(".edit-field").forEach(field => field.removeAttribute("readonly"));
        document.getElementById("btnModificar").style.display = "none";
        document.getElementById("btnGuardar").style.display = "inline-block";
    });

    document.getElementById("btnGuardar").addEventListener("click", function() {
        let equipos = [];
        document.querySelectorAll(".equipo-item").forEach(item => {
            let id = item.getAttribute("data-id");
            let descripcion = item.querySelector(".desc-input").value;
            let cantidad = item.querySelector(".cant-input").value;
            let marca = item.querySelector(".marca-input").value;

            equipos.push({ id, descripcion, cantidad, marca });
        });

        fetch("guardar_modificacion.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                equipos: equipos,
                aula_id: window.aulaActualId  // Añadir el ID del aula aquí
            })
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                // Configurar y mostrar el toast
                const toastEl = document.getElementById('successToast');
                const toast = new bootstrap.Toast(toastEl, {
                    animation: true,
                    autohide: true,
                    delay: 3000
                });
                toast.show();
                
                // Cerrar el modal
                const modalEl = document.getElementById('modalEquipos');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                
                document.getElementById("btnModificar").style.display = "inline-block";
                document.getElementById("btnGuardar").style.display = "none";
                document.querySelectorAll(".edit-field").forEach(field => field.setAttribute("readonly", true));
            } else {
                alert("Error al actualizar los equipos: " + data);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});

function agregarEquipo() {
    let lista = document.getElementById("equiposLista");
    let nuevoEquipo = document.createElement("div");
    nuevoEquipo.classList.add("equipo-item");
    nuevoEquipo.dataset.id = "0"; // ID 0 indica que es nuevo

    nuevoEquipo.innerHTML = `
        <input type="text" class="desc-input edit-field" placeholder="Descripción" required>
        <input type="number" class="cant-input edit-field" placeholder="Cantidad" required>
        <input type="text" class="marca-input edit-field" placeholder="Marca" required>
    `;

    lista.appendChild(nuevoEquipo);
}
</script>

</body>
</html>
