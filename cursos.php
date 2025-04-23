<?php
session_start();
include "config.php";

// Query to get courses, career, shift, and number of enrolled students from the new alumnos table
$sql = "
SELECT 
    cu.nombre AS curso, 
    ca.nombre AS carrera, 
    cu.turno, 
    COUNT(a.cedula_identidad) AS alumnos_matriculados
FROM cursos cu
LEFT JOIN carreras ca ON cu.carrera_id = ca.id
LEFT JOIN alumnos a 
    ON a.carrera = ca.nombre
    AND a.curso = cu.nombre
    AND a.turno = cu.turno
GROUP BY cu.nombre, ca.nombre, cu.turno
ORDER BY ca.nombre, cu.nombre, cu.turno
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            min-height: 100vh;
        }
        .table-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-top: 50px;
        }
        h2 {
            color: #1e3c72;
        }
        .main-menu-btn {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <a href="dashboard.php" class="btn btn-primary main-menu-btn">Menu Principal</a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 table-section">
                <h2 class="mb-4 text-center">Cursos y Alumnos Matriculados</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>Curso</th>
                                <th>Carrera</th>
                                <th>Turno</th>
                                <th>Alumnos Matriculados</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['curso'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['carrera'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['turno'] ?? '') ?></td>
                                <td><?= $row['alumnos_matriculados'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>