<?php
session_start();
include "config.php";

// Registrar nuevo alumno
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = trim($_POST['cedula_identidad']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $carrera = $_POST['carrera'];
    $curso = $_POST['curso'];
    $turno = $_POST['turno'];
    $contacto = trim($_POST['contacto']);

    // Unificar el valor de curso para que coincida con la tabla cursos
    $curso_map = [
        'Primero' => 'PRIMER',
        'Segundo' => 'SEGUNDO',
        'Tercero' => 'TERCER',
        'Cuarto' => 'CUARTO',
        'Quinto' => 'QUINTO'
    ];
    if (isset($curso_map[$curso])) {
        $curso = $curso_map[$curso];
    }

    // Verificar si la cédula ya existe
    $check = $conn->prepare("SELECT 1 FROM alumnos WHERE cedula_identidad = ?");
    $check->bind_param("s", $cedula);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $error = "Ya existe un alumno con esa cédula de identidad.";
    } else {
        $sql = "INSERT INTO alumnos (cedula_identidad, nombre, apellido, carrera, curso, turno, contacto)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $cedula, $nombre, $apellido, $carrera, $curso, $turno, $contacto);
        $stmt->execute();
    }
    $check->close();
}

// Listar alumnos
$sql = "SELECT cedula_identidad, nombre, apellido, carrera, curso, turno, contacto FROM alumnos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Alumnos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            min-height: 100vh;
        }
        .form-section, .table-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .form-section {
            margin-bottom: 30px;
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
            <div class="col-lg-8">
                <a href="dashboard.php" class="btn btn-primary main-menu-btn">Menu Principal</a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 form-section">
                <h2 class="mb-4 text-center">Registrar Alumno</h2>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="cedula_identidad" class="form-control" placeholder="Cédula de Identidad" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="apellido" class="form-control" placeholder="Apellido" required>
                    </div>
                    <div class="col-md-4">
                        <select name="carrera" class="form-select" required>
                            <option value="">Seleccione Carrera</option>
                            <option value="Administración de Empresas">Administración de Empresas</option>
                            <option value="Contabilidad">Contabilidad</option>
                            <option value="Comercio Internacional">Comercio Internacional</option>
                            <option value="Ingenieria Comercial">Ingenieria Comercial</option>
                            <option value="Ingenieria en Informatica">Ingenieria en Informatica</option>
                            <option value="Ingenieria en Marketing">Ingenieria en Marketing</option>
                            <option value="Lic. en Análisis de Sistema">Lic. en Análisis de Sistema</option>
                            <option value="Diseño Grafico">Diseño Grafico</option>
                            <option value="Arquitectura">Arquitectura</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="curso" class="form-select" required>
                            <option value="">Seleccione Curso</option>
                            <option value="PRIMER">PRIMER</option>
                            <option value="SEGUNDO">SEGUNDO</option>
                            <option value="TERCER">TERCER</option>
                            <option value="CUARTO">CUARTO</option>
                            <option value="QUINTO">QUINTO</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="turno" class="form-select" required>
                            <option value="">Seleccione Turno</option>
                            <option value="Mañana">Mañana</option>
                            <option value="Tarde">Tarde</option>
                            <option value="Noche">Noche</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="contacto" class="form-control" placeholder="Teléfono" required>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-danger w-50">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-lg-10 table-section">
                <h2 class="mb-4 text-center">Lista de Alumnos</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>Cédula</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Carrera</th>
                                <th>Curso</th>
                                <th>Turno</th>
                                <th>Contacto</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['cedula_identidad']) ?></td>
                                <td><?= htmlspecialchars($row['nombre']) ?></td>
                                <td><?= htmlspecialchars($row['apellido']) ?></td>
                                <td><?= htmlspecialchars($row['carrera']) ?></td>
                                <td><?= htmlspecialchars($row['curso']) ?></td>
                                <td><?= htmlspecialchars($row['turno']) ?></td>
                                <td><?= htmlspecialchars($row['contacto']) ?></td>
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