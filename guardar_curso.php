<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $turno = $conn->real_escape_string($_POST['turno']);
    $carrera_id = intval($_POST['carrera_id']);
    $alumnos_matriculados = intval($_POST['alumnos_matriculados']);

    $query = "INSERT INTO cursos (nombre, turno, carrera_id, alumnos_matriculados) 
              VALUES (?, ?, ?, ?)";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $nombre, $turno, $carrera_id, $alumnos_matriculados);
    
    if ($stmt->execute()) {
        header("Location: cursos.php?success=1&mensaje=Curso agregado correctamente");
    } else {
        header("Location: cursos.php?error=1&mensaje=Error al agregar el curso");
    }
    $stmt->close();
    exit;
}

header("Location: cursos.php");
?>