<?php
include("config.php");

$query = "SELECT e.*, 
          COALESCE(MAX(hm.fecha_mantenimiento), e.fecha_instalacion) as ultima_fecha_mantenimiento,
          DATEDIFF(
              COALESCE(MAX(hm.fecha_mantenimiento), e.fecha_instalacion) + INTERVAL e.periodo_mantenimiento DAY,
              CURRENT_DATE
          ) as dias_restantes,
          ae.aula_id,
          e.nro_serie as patrimonio
          FROM equipos e
          LEFT JOIN aulas_equipos ae ON e.id = ae.equipo_id
          LEFT JOIN historial_mantenimiento hm ON e.id = hm.equipo_id
          GROUP BY e.id, ae.aula_id
          ORDER BY e.descripcion";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="dashboard.php" class="btn btn-primary">
                <i class="bi bi-arrow-left-circle"></i> Menú Principal
            </a>
            <h2>Gestión de Inventario</h2>
            <div>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalIngresarEquipo">
                    <i class="bi bi-plus-circle"></i> Ingresar Equipos
                </button>
                <a href="reporte_sin_asignar.php" class="btn btn-info">
                    <i class="bi bi-file-text"></i> Equipos Sin Asignar
                </a>
                <a href="reporte_asignados.php" class="btn btn-primary">
                    <i class="bi bi-file-text"></i> Equipos Asignados
                </a>
            </div>
        </div>

        <!-- Barra de búsqueda -->
        <div class="mb-3">
            <input type="text" id="busquedaInventario" class="form-control" placeholder="Buscar en la tabla...">
        </div>
        <!-- Fin barra de búsqueda -->

        <table class="table table-bordered" id="tablaInventario">
            <thead class="table-dark">
                <tr>
                    <th>Aula</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>N° Factura</th>
                    <th>Estado Asignación</th>
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
                        <td>
                            <?php 
                            if ($row['aula_id']) {
                                $aula_query = "SELECT numero FROM aulas WHERE id = " . $row['aula_id'];
                                $aula_result = $conn->query($aula_query);
                                $aula = $aula_result->fetch_assoc();
                                echo "Aula " . $aula['numero'];
                            } else {
                                echo 'Sin asignar';
                            }
                            ?>
                        </td>
                        <td><?php echo $row['tipo']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td><?php echo $row['nro_factura']; ?></td>
                        <td>
                            <?php if ($row['aula_id']): ?>
                                <span class="badge bg-success">
                                    <?php 
                                    $aula_query = "SELECT numero FROM aulas WHERE id = " . $row['aula_id'];
                                    $aula_result = $conn->query($aula_query);
                                    $aula = $aula_result->fetch_assoc();
                                    echo "Asignado al Aula " . $aula['numero'];
                                    ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning">Sin Asignar</span>
                            <?php endif; ?>
                        </td>
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
                        <!-- Replace the actions column TD with this -->
                        <td>
                            <div class="btn-group-vertical w-100">
                                <button class="btn btn-warning btn-sm" onclick="editarEquipo(<?php echo $row['id']; ?>)">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarEquipo(<?php echo htmlspecialchars($row['id']); ?>)">
                                    <i class="bi bi-trash"></i> Eliminar
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
    

    <!-- All modals here -->
    <!-- Modal para Ingresar Equipos -->
    <div class="modal fade" id="modalIngresarEquipo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ingresar Nuevos Equipos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formIngresarEquipo">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Número de Factura</label>
                                <input type="text" class="form-control" name="nro_factura" id="nro_factura" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Patrimonio</label>
                                <input type="text" class="form-control" name="patrimonio" id="patrimonio" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control" name="descripcion" id="descripcion" required></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cantidad</label>
                                <input type="number" class="form-control" name="cantidad" id="cantidad" value="1" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Marca</label>
                                <input type="text" class="form-control" name="marca" id="marca" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Modelo</label>
                                <input type="text" class="form-control" name="modelo" id="modelo" required>
                            </div>
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
                        <input type="hidden" name="equipo_id" id="equipo_id">
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <input type="text" class="form-control" name="tipo" id="tipo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Marca</label>
                            <input type="text" class="form-control" name="marca" id="marca" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Modelo</label>
                            <input type="text" class="form-control" name="modelo" id="modelo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">N° Serie/Patrimonio</label>
                            <input type="text" class="form-control" name="nro_serie" id="nro_serie" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha Instalación</label>
                            <input type="date" class="form-control" name="fecha_instalacion" id="fecha_instalacion" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Período de Mantenimiento (días)</label>
                            <input type="number" class="form-control" name="periodo_mantenimiento" id="periodo_mantenimiento" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEdicionEquipo()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Mantenimiento -->
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

    <!-- Modal para Historial -->
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

    <!-- Scripts at the end -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function eliminarEquipo(id) {
            if (confirm('¿Está seguro que desea eliminar este equipo?')) {
                fetch('eliminar_equipo.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Equipo eliminado correctamente');
                        window.location.reload();
                    } else {
                        alert('Error al eliminar el equipo: ' + (data.error || 'Error desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el equipo');
                });
            }
        }

        function editarEquipo(id) {
            fetch('obtener_equipo.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    // Asegurarse de que los IDs coincidan exactamente con los del formulario
                    document.querySelector('#formEditarEquipo input[name="equipo_id"]').value = data.id;
                    document.querySelector('#formEditarEquipo input[name="tipo"]').value = data.tipo;
                    document.querySelector('#formEditarEquipo input[name="marca"]').value = data.marca;
                    document.querySelector('#formEditarEquipo input[name="modelo"]').value = data.modelo;
                    document.querySelector('#formEditarEquipo input[name="nro_serie"]').value = data.nro_serie;
                    document.querySelector('#formEditarEquipo input[name="fecha_instalacion"]').value = data.fecha_instalacion;
                    document.querySelector('#formEditarEquipo input[name="periodo_mantenimiento"]').value = data.periodo_mantenimiento;
                    
                    var modal = new bootstrap.Modal(document.getElementById('modalEditarEquipo'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del equipo');
                });
        }

        function registrarMantenimiento(id) {
            document.getElementById('mantenimiento_equipo_id').value = id;
            document.getElementById('fecha_mantenimiento').value = new Date().toISOString().split('T')[0];
            var modal = new bootstrap.Modal(document.getElementById('modalMantenimiento'));
            modal.show();
        }

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

        function guardarEquipo() {
            const formData = new FormData(document.getElementById('formIngresarEquipo'));
            
            fetch('guardar_equipo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Equipos guardados correctamente');
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        }

        function guardarEdicionEquipo() {
            const formData = new FormData(document.getElementById('formEditarEquipo'));
            
            fetch('actualizar_equipo.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Crear el modal de éxito
                    const successModal = document.createElement('div');
                    successModal.className = 'modal fade show';
                    successModal.style.display = 'block';
                    successModal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                    successModal.innerHTML = `
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-check-circle"></i> Éxito
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" onclick="this.closest('.modal').remove()"></button>
                                </div>
                                <div class="modal-body">
                                    Equipos actualizados correctamente
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" onclick="this.closest('.modal').remove()">Aceptar</button>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(successModal);

                    // Remover el modal y recargar después de hacer clic en Aceptar
                    successModal.querySelector('.btn-primary').addEventListener('click', () => {
                        location.reload();
                    });
                } else {
                    alert(data.message || 'Error al actualizar el equipo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        }
    </script>
    <script>
    // Filtro de búsqueda en la tabla de inventario usando jQuery
    $(document).ready(function(){
        $("#busquedaInventario").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#tablaInventario tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
    </script>
</body>
</html>
