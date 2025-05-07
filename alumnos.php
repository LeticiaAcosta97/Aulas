<?php
session_start();
include "config.php";

// --- Add search filter logic ---
$search = '';
$where = '';
if (isset($_GET['buscar']) && trim($_GET['buscar']) !== '') {
    $search = trim($_GET['buscar']);
    $where = "WHERE cedula_identidad LIKE ? OR nombre LIKE ? OR apellido LIKE ?";
}

// --- Add sorting logic ---
$allowed_columns = ['cedula_identidad', 'nombre', 'apellido', 'carrera', 'curso', 'turno', 'contacto'];
$order_by = 'cedula_identidad'; // default column
$order_dir = 'ASC'; // default direction

if (isset($_GET['sort']) && in_array($_GET['sort'], $allowed_columns)) {
    $order_by = $_GET['sort'];
}
if (isset($_GET['dir']) && in_array(strtoupper($_GET['dir']), ['ASC', 'DESC'])) {
    $order_dir = strtoupper($_GET['dir']);
}

// Registrar nuevo alumno
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['filtro_busqueda'])) {
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

// --- Modify the SQL query for listing students ---
if ($where) {
    $sql = "SELECT cedula_identidad, nombre, apellido, carrera, curso, turno, contacto FROM alumnos $where ORDER BY $order_by $order_dir";
    $stmt = $conn->prepare($sql);
    $like = "%$search%";
    $stmt->bind_param("sss", $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $sql = "SELECT cedula_identidad, nombre, apellido, carrera, curso, turno, contacto FROM alumnos ORDER BY $order_by $order_dir";
    $result = $conn->query($sql);
}
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
        <!-- --- Add search form here --- -->
        <div class="row justify-content-center mb-3">
            <div class="col-lg-8">
                <form method="GET" class="d-flex">
                    <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por cédula, nombre o apellido" value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-success">Buscar</button>
                    <?php if($search): ?>
                        <a href="alumnos.php" class="btn btn-secondary ms-2">Limpiar</a>
                    <?php endif; ?>
                </form>
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
                                <?php
                                // Helper for sort links
                                function sort_link($label, $column, $order_by, $order_dir, $search) {
                                    $dir = ($order_by === $column && $order_dir === 'ASC') ? 'DESC' : 'ASC';
                                    $arrow = '';
                                    if ($order_by === $column) {
                                        $arrow = $order_dir === 'ASC' ? ' ▲' : ' ▼';
                                    }
                                    $params = [
                                        'sort' => $column,
                                        'dir' => $dir
                                    ];
                                    if ($search) $params['buscar'] = $search;
                                    $url = '?' . http_build_query($params);
                                    return "<a href=\"$url\">$label$arrow</a>";
                                }
                                ?>
                                <th><?= sort_link('Cédula', 'cedula_identidad', $order_by, $order_dir, $search) ?></th>
                                <th><?= sort_link('Nombre', 'nombre', $order_by, $order_dir, $search) ?></th>
                                <th><?= sort_link('Apellido', 'apellido', $order_by, $order_dir, $search) ?></th>
                                <th><?= sort_link('Carrera', 'carrera', $order_by, $order_dir, $search) ?></th>
                                <th><?= sort_link('Curso', 'curso', $order_by, $order_dir, $search) ?></th>
                                <th><?= sort_link('Turno', 'turno', $order_by, $order_dir, $search) ?></th>
                                <th><?= sort_link('Contacto', 'contacto', $order_by, $order_dir, $search) ?></th>
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