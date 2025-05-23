<?php
include("config.php");

$query = "SELECT e.*, a.numero as numero_aula, 
          COALESCE(MAX(hm.fecha_mantenimiento), e.fecha_instalacion) as ultimo_mantenimiento,
          e.periodo_mantenimiento
          FROM equipos e 
          INNER JOIN aulas_equipos ae ON e.id = ae.equipo_id
          INNER JOIN aulas a ON ae.aula_id = a.id
          LEFT JOIN historial_mantenimiento hm ON e.id = hm.equipo_id
          GROUP BY e.id, a.numero
          ORDER BY a.numero, e.descripcion";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos Asignados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="gestion_inventario.php" class="btn btn-primary">
                <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
            <h2>Equipos Asignados</h2>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="bi bi-printer"></i> Imprimir
            </button>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Aula</th>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>N° Factura</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>N° Serie/Patrimonio</th>
                    <th>Fecha Instalación</th>
                    <th>Último Mantenimiento</th>
                    <th>Periodo Mant.</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $current_aula = null;
                while ($row = $result->fetch_assoc()) { 
                    if ($current_aula !== $row['numero_aula']) {
                        echo '<tr class="table-secondary"><th colspan="10">Aula: ' . $row['numero_aula'] . '</th></tr>';
                        $current_aula = $row['numero_aula'];
                    }
                ?>
                    <tr>
                        <td><?php echo $row['numero_aula']; ?></td>
                        <td><?php echo $row['tipo']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td><?php echo $row['nro_factura']; ?></td>
                        <td><?php echo $row['marca']; ?></td>
                        <td><?php echo $row['modelo']; ?></td>
                        <td><?php echo $row['nro_serie']; ?></td>
                        <td><?php echo $row['fecha_instalacion']; ?></td>
                        <td><?php echo $row['ultimo_mantenimiento']; ?></td>
                        <td><?php echo $row['periodo_mantenimiento']; ?> días</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>