<?php
include("config.php");

$query = "SELECT e.*, a.numero as numero_aula,
          DATEDIFF(DATE_ADD(IFNULL(e.ultima_fecha_mantenimiento, CURDATE()), 
          INTERVAL e.periodo_mantenimiento DAY), CURDATE()) as dias_restantes
          FROM equipos e 
          LEFT JOIN aulas a ON e.aula_id = a.id 
          ORDER BY a.numero";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="dashboard.php" class="btn btn-primary">
                <i class="bi bi-arrow-left-circle"></i> Menú Principal
            </a>
            <h2>Gestión de Inventario</h2>
            <div>
                <a href="reporte_inventario.php" class="btn btn-info">
                    <i class="bi bi-file-text"></i> Ver Reporte
                </a>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Aula</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>N° Serie/Patrimonio</th>
                    <th>Fecha Instalación</th>
                    <th>Último Mantenimiento</th>
                    <th>Observaciones</th>
                    <th>Período Mant.</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { 
                    $dias_restantes = $row['dias_restantes'];
                    $estado_class = '';
                    $estado_text = '';
                    
                    if ($dias_restantes < 0) {
                        $estado_class = 'bg-danger text-white';
                        $estado_text = 'Mantenimiento Vencido';
                    } elseif ($dias_restantes <= 15) {
                        $estado_class = 'bg-warning';
                        $estado_text = 'Próximo a Vencer';
                    } else {
                        $estado_class = 'bg-success text-white';
                        $estado_text = 'Al día';
                    }
                ?>
                    <tr>
                        <td><?php echo $row['numero_aula']; ?></td>
                        <td><?php echo $row['tipo']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td><?php echo $row['marca']; ?></td>
                        <td><?php echo $row['modelo']; ?></td>
                        <td><?php echo $row['nro_serie']; ?></td>
                        <td><?php echo $row['fecha_instalacion']; ?></td>
                        <td class="<?php echo $estado_class; ?>">
                            <?php echo $row['ultima_fecha_mantenimiento']; ?>
                            <div><small><?php echo $estado_text; ?></small></div>
                        </td>
                        <td><?php echo $row['observaciones']; ?></td>
                        <td><?php echo $row['periodo_mantenimiento']; ?> días</td>
                        <td>
                            <div class="d-grid gap-2">
                                <button class="btn btn-warning btn-sm" onclick="editarEquipo(<?php echo $row['id']; ?>)">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <button class="btn btn-info btn-sm" onclick="registrarMantenimiento(<?php echo $row['id']; ?>)">
                                    <i class="bi bi-tools"></i> Mantenimiento
                                </button>
                                <button class="btn btn-primary btn-sm" onclick="verHistorial(<?php echo $row['id']; ?>)">
                                    <i class="bi bi-clock-history"></i> Historial
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para editar equipo -->
    <div class="modal fade" id="modalEditarEquipo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarEquipo">
                        <input type="hidden" id="equipo_id">
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control" id="tipo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Modelo</label>
                            <input type="text" class="form-control" id="modelo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">N° Serie/Patrimonio</label>
                            <input type="text" class="form-control" id="nro_serie" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Instalación</label>
                            <input type="date" class="form-control" id="fecha_instalacion" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Período de Mantenimiento (días)</label>
                            <input type="number" class="form-control" id="periodo_mantenimiento" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEquipo()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Eliminar script duplicado y corregir la estructura -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function editarEquipo(id) {
                fetch('obtener_equipo.php?id=' + id)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('equipo_id').value = data.id;
                        document.getElementById('tipo').value = data.tipo;
                        document.getElementById('marca').value = data.marca;
                        document.getElementById('modelo').value = data.modelo;
                        document.getElementById('nro_serie').value = data.nro_serie;
                        document.getElementById('fecha_instalacion').value = data.fecha_instalacion;
                        document.getElementById('periodo_mantenimiento').value = data.periodo_mantenimiento;
                        
                        var modal = new bootstrap.Modal(document.getElementById('modalEditarEquipo'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al cargar los datos del equipo');
                    });
            }
    
            function guardarEquipo() {
                const formData = new FormData();
                formData.append('equipo_id', document.getElementById('equipo_id').value);
                formData.append('tipo', document.getElementById('tipo').value);
                formData.append('marca', document.getElementById('marca').value);
                formData.append('modelo', document.getElementById('modelo').value);
                formData.append('nro_serie', document.getElementById('nro_serie').value);
                formData.append('fecha_instalacion', document.getElementById('fecha_instalacion').value);
                formData.append('periodo_mantenimiento', document.getElementById('periodo_mantenimiento').value);
        
                fetch('actualizar_equipo.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cerrar modal
                        bootstrap.Modal.getInstance(document.getElementById('modalEditarEquipo')).hide();
                        // Recargar página
                        location.reload();
                    } else {
                        alert('Error al guardar los cambios: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al guardar los cambios');
                });
            }
        </script>
    <!-- Agregar este modal antes del cierre del div container -->
    <div class="modal fade" id="modalMantenimiento" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Mantenimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formMantenimiento">
                        <input type="hidden" id="mantenimiento_equipo_id">
                        <div class="mb-3">
                            <label class="form-label">Fecha de Mantenimiento</label>
                            <input type="date" class="form-control" id="fecha_mantenimiento" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarMantenimiento()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Actualizar las funciones JavaScript -->
    <script>
        function registrarMantenimiento(id) {
            document.getElementById('mantenimiento_equipo_id').value = id;
            document.getElementById('fecha_mantenimiento').value = new Date().toISOString().split('T')[0];
            var modal = new bootstrap.Modal(document.getElementById('modalMantenimiento'));
            modal.show();
        }

        function guardarMantenimiento() {
            const formData = new FormData();
            formData.append('equipo_id', document.getElementById('mantenimiento_equipo_id').value);
            formData.append('fecha_mantenimiento', document.getElementById('fecha_mantenimiento').value);
            formData.append('observaciones', document.getElementById('observaciones').value);

            fetch('registrar_mantenimiento.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalMantenimiento')).hide();
                    location.reload();
                } else {
                    alert('Error al registrar el mantenimiento: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al registrar el mantenimiento');
            });
        }
    </script>

    <!-- Agregar Modal para Historial -->
    <div class="modal fade" id="modalHistorial" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Historial de Mantenimiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="historialContenido"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agregar función JavaScript para ver historial -->
    <script>
    function verHistorial(id) {
        fetch('obtener_historial.php?id=' + id)
            .then(response => response.text())
            .then(data => {
                document.getElementById('historialContenido').innerHTML = data;
                var modal = new bootstrap.Modal(document.getElementById('modalHistorial'));
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar el historial');
            });
    }
    </script>
</body>
</html>
