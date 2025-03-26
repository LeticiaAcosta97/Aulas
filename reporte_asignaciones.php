<?php
include("config.php");

$query = "SELECT a.numero AS aula, c.nombre AS curso, c.turno, ca.nombre AS carrera
          FROM asignaciones asg
          INNER JOIN aulas a ON asg.aula_id = a.id
          INNER JOIN cursos c ON asg.curso_id = c.id
          INNER JOIN carreras ca ON c.carrera_id = ca.id";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asignaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .page-header {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        .table {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .table thead {
            background-color: #1D3557;
            color: white;
        }
        .table thead th {
            border-bottom: none;
            padding: 15px;
        }
        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .btn-primary {
            background-color: #1D3557;
            border: none;
            padding: 10px 20px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #152843;
            transform: translateY(-2px);
        }
        .stats-container {
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <a href="asignacion_aulas.php" class="btn btn-primary">
                    <i class="bi bi-arrow-left-circle"></i> Volver a Asignaciones
                </a>
                <h2 class="mb-0">Reporte de Asignaciones</h2>
                <div class="btn-group">
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>

        <div class="row stats-container">
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <h4 class="mb-0"><?php echo $result->num_rows; ?></h4>
                    <small class="text-muted">Total Asignaciones</small>
                </div>
            </div>
            <!-- Puedes agregar más estadísticas aquí -->
        </div>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Aula</th>
                    <th>Curso</th>
                    <th>Turno</th>
                    <th>Carrera</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><strong><?php echo $row['aula']; ?></strong></td>
                        <td><?php echo $row['curso']; ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo $row['turno']; ?></span>
                        </td>
                        <td><?php echo $row['carrera']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
