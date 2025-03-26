<?php
include("config.php");

// Consulta para obtener el resumen de equipos
$query = "SELECT 
            tipo,
            marca,
            modelo,
            COUNT(*) as cantidad_total,
            GROUP_CONCAT(DISTINCT a.numero) as aulas
          FROM equipos e
          LEFT JOIN aulas a ON e.aula_id = a.id
          GROUP BY tipo, marca, modelo
          ORDER BY tipo, marca, modelo";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="gestion_inventario.php" class="btn btn-primary">
                <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
            <h2>Reporte de Inventario</h2>
            <button class="btn btn-success" onclick="exportarPDF()">
                <i class="bi bi-file-pdf"></i> Exportar PDF
            </button>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Tipo de Equipo</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Cantidad Total</th>
                            <th>Ubicación (Aulas)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_equipos = 0;
                        while ($row = $result->fetch_assoc()) { 
                            $total_equipos += $row['cantidad_total'];
                        ?>
                            <tr>
                                <td><?php echo $row['tipo']; ?></td>
                                <td><?php echo $row['marca']; ?></td>
                                <td><?php echo $row['modelo']; ?></td>
                                <td class="text-center"><?php echo $row['cantidad_total']; ?></td>
                                <td><?php echo $row['aulas']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total de Equipos:</strong></td>
                            <td class="text-center"><strong><?php echo $total_equipos; ?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Gráfico de distribución -->
        <div class="card">
            <div class="card-header">
                <h5>Distribución de Equipos por Tipo</h5>
            </div>
            <div class="card-body">
                <canvas id="graficoDistribucion"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Función para exportar a PDF
        function exportarPDF() {
            window.print();
        }

        // Configuración del gráfico
        const ctx = document.getElementById('graficoDistribucion').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php 
                    $result->data_seek(0);
                    $tipos = array();
                    $cantidades = array();
                    while ($row = $result->fetch_assoc()) {
                        $tipos[] = $row['tipo'];
                        $cantidades[] = $row['cantidad_total'];
                    }
                    echo json_encode($tipos);
                ?>,
                datasets: [{
                    label: 'Cantidad de Equipos',
                    data: <?php echo json_encode($cantidades); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>