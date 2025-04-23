<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $turno = $conn->real_escape_string($_POST['turno']);
    $carrera_id = intval($_POST['carrera_id']);
    $alumnos_matriculados = intval($_POST['alumnos_matriculados']);

    $query = "UPDATE cursos SET nombre = ?, turno = ?, carrera_id = ?, 
              alumnos_matriculados = ? WHERE id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiii", $nombre, $turno, $carrera_id, $alumnos_matriculados, $id);
    
    if ($stmt->execute()) {
        header("Location: cursos.php?success=1&mensaje=Curso actualizado correctamente");
    } else {
        header("Location: cursos.php?error=1&mensaje=Error al actualizar el curso");
    }
    $stmt->close();
    exit;
}

header("Location: cursos.php");
?>