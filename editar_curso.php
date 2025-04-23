<?php
include("config.php");

if (!isset($_GET['id'])) {
    header("Location: cursos.php?error=1&mensaje=ID del curso no proporcionado");
    exit;
}

$curso_id = intval($_GET['id']);

// Obtener información del curso
$curso_query = "SELECT c.* FROM cursos c WHERE c.id = ?";
$stmt = $conn->prepare($curso_query);
$stmt->bind_param("i", $curso_id);
$stmt->execute();
$curso = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Obtener lista de carreras
$carreras_query = "SELECT id, nombre FROM carreras ORDER BY nombre";
$carreras = $conn->query($carreras_query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Curso</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Editar Curso</h2>
            <a href="cursos.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Volver
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="actualizar_curso.php" method="POST" class="row g-3">
                    <input type="hidden" name="id" value="<?php echo $curso['id']; ?>">
                    
                    <div class="col-md-4">
                        <label class="form-label">Nombre del Curso</label>
                        <input type="text" class="form-control" name="nombre" 
                               value="<?php echo $curso['nombre']; ?>" required>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Turno</label>
                        <select class="form-select" name="turno" required>
                            <option value="Mañana" <?php echo $curso['turno'] == 'Mañana' ? 'selected' : ''; ?>>Mañana</option>
                            <option value="Tarde" <?php echo $curso['turno'] == 'Tarde' ? 'selected' : ''; ?>>Tarde</option>
                            <option value="Noche" <?php echo $curso['turno'] == 'Noche' ? 'selected' : ''; ?>>Noche</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Carrera</label>
                        <select class="form-select" name="carrera_id" required>
                            <?php while ($carrera = $carreras->fetch_assoc()) { ?>
                                <option value="<?php echo $carrera['id']; ?>" 
                                        <?php echo $carrera['id'] == $curso['carrera_id'] ? 'selected' : ''; ?>>
                                    <?php echo $carrera['nombre']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Alumnos Matriculados</label>
                        <input type="number" class="form-control" name="alumnos_matriculados" 
                               value="<?php echo $curso['alumnos_matriculados']; ?>" required>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>