<?php
include("config.php");

if (!isset($_GET['aula_id'])) {
    header("Location: asignacion_aulas.php");
    exit;
}

$aula_id = intval($_GET['aula_id']);

// Obtener información del aula
$aula_query = "SELECT numero FROM aulas WHERE id = ?";
$stmt = $conn->prepare($aula_query);
$stmt->bind_param("i", $aula_id);
$stmt->execute();
$aula = $stmt->get_result()->fetch_assoc();

// Obtener lista de cursos con sus detalles
$query = "SELECT c.id, c.nombre as curso, c.turno, c.alumnos_matriculados, 
          car.nombre as carrera
          FROM cursos c
          LEFT JOIN carreras car ON c.carrera_id = car.id
          ORDER BY car.nombre, c.nombre";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Curso al Aula <?php echo $aula['numero']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Asignar Curso al Aula <?php echo $aula['numero']; ?></h2>
        
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Turno</th>
                    <th>Carrera</th>
                    <th>Alumnos Matriculados</th>
                    <th>Seleccionar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['curso']; ?></td>
                        <td><?php echo $row['turno']; ?></td>
                        <td><?php echo $row['carrera']; ?></td>
                        <td><?php echo $row['alumnos_matriculados']; ?></td>
                        <td>
                            <form method="POST" action="guardar_asignacion.php" style="display: inline;">
                                <input type="hidden" name="aula_id" value="<?php echo $aula_id; ?>">
                                <input type="hidden" name="curso_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Asignar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="asignacion_aulas.php" class="btn btn-secondary">Cancelar</a>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
