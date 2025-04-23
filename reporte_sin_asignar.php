<?php
include("config.php");

$query = "SELECT e.* 
          FROM equipos e 
          LEFT JOIN aulas_equipos ae ON e.id = ae.equipo_id 
          WHERE ae.id IS NULL 
          ORDER BY e.nro_factura";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos Sin Asignar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <a href="gestion_inventario.php" class="btn btn-primary mb-3">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
        <h2 class="text-center mb-4">Equipos Sin Asignar</h2>
        
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>N° Factura</th>
                    <th>Patrimonio</th>
                    <th>Descripción</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['nro_factura']; ?></td>
                        <td><?php echo $row['nro_serie']; ?></td>
                        <td><?php echo $row['descripcion']; ?></td>
                        <td><?php echo $row['marca']; ?></td>
                        <td><?php echo $row['modelo']; ?></td>
                        <td><span class="badge bg-warning">Sin Asignar</span></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>