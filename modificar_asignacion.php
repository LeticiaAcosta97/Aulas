<?php
include("config.php");
  
if (!isset($_GET['asignacion_id'])) {
    header("Location: asignacion_aulas.php?error=1&mensaje=ID de asignación no proporcionado");
    exit;
}

$asignacion_id = intval($_GET['asignacion_id']);

// Obtener información de la asignación actual
$asignacion_query = "SELECT a.*, aul.numero as aula_numero, aul.id as aula_id,
                     c.id as curso_actual_id, c.nombre as curso_actual, 
                     c.turno as turno_actual, car.nombre as carrera_actual
                     FROM asignaciones a
                     JOIN aulas aul ON a.aula_id = aul.id
                     JOIN cursos c ON a.curso_id = c.id
                     JOIN carreras car ON c.carrera_id = car.id
                     WHERE a.id = ?";
$stmt = $conn->prepare($asignacion_query);
$stmt->bind_param("i", $asignacion_id);
$stmt->execute();
$asignacion = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Obtener lista de cursos disponibles
$sql = "SELECT c.id, c.nombre AS curso, c.turno, car.nombre as carrera,
        (
            SELECT COUNT(*) FROM alumnos a
            WHERE a.carrera = car.nombre
              AND a.curso = c.nombre
              AND a.turno = c.turno
        ) AS alumnos_matriculados
        FROM cursos c
        JOIN carreras car ON c.carrera_id = car.id
        ORDER BY car.nombre, c.nombre";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Asignación - Aula <?php echo $asignacion['aula_numero']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Modificar Asignación - Aula <?php echo $asignacion['aula_numero']; ?></h2>
        
        <div class="alert alert-info mb-4">
            <h5>Asignación Actual:</h5>
            <p>
                <strong>Carrera:</strong> <?php echo $asignacion['carrera_actual']; ?><br>
                <strong>Curso:</strong> <?php echo $asignacion['curso_actual']; ?><br>
                <strong>Turno:</strong> <?php echo $asignacion['turno_actual']; ?>
            </p>
        </div>

        <form method="POST" action="actualizar_asignacion.php">
            <input type="hidden" name="asignacion_id" value="<?php echo $asignacion_id; ?>">
            <input type="hidden" name="aula_id" value="<?php echo $asignacion['aula_id']; ?>">
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Turno</th>
                            <th>Carrera</th>
                            <th>Alumnos</th>
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                               name="curso_id" 
                                               value="<?php echo $row['id']; ?>"
                                               <?php echo ($row['id'] == $asignacion['curso_actual_id']) ? 'checked' : ''; ?>>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="asignacion_aulas.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
