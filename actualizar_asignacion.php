<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $asignacion_id = intval($_POST['asignacion_id']);
    $aula_id = intval($_POST['aula_id']);
    $curso_id = intval($_POST['curso_id']);
    
    // Obtener el turno del nuevo curso
    $get_curso_turno = "SELECT c.turno FROM cursos c WHERE c.id = ?";
    $stmt = $conn->prepare($get_curso_turno);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $curso_actual = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    // Verificar si el aula ya tiene una asignación en el mismo turno (excluyendo la asignación actual)
    $check_turno_query = "SELECT a.*, c.nombre as curso, c.turno 
                         FROM asignaciones a
                         JOIN cursos c ON a.curso_id = c.id
                         WHERE a.aula_id = ? AND c.turno = ? AND a.id != ?";
    $stmt = $conn->prepare($check_turno_query);
    $stmt->bind_param("isi", $aula_id, $curso_actual['turno'], $asignacion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $asignacion = $result->fetch_assoc();
        $stmt->close();
        header("Location: asignacion_aulas.php?error=1&mensaje=El aula ya tiene un curso asignado en el turno " . 
               urlencode($curso_actual['turno']));
        exit;
    }
    $stmt->close();
    
    // Verificar si el nuevo curso ya está asignado a otra aula
    $check_curso_query = "SELECT a.*, aul.numero as numero_aula 
                         FROM asignaciones a
                         JOIN aulas aul ON a.aula_id = aul.id
                         WHERE a.curso_id = ? AND a.id != ?";
    $stmt = $conn->prepare($check_curso_query);
    $stmt->bind_param("ii", $curso_id, $asignacion_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $asignacion = $result->fetch_assoc();
        $stmt->close();
        header("Location: asignacion_aulas.php?error=1&mensaje=Este curso ya está asignado a otra aula");
        exit;
    }
    $stmt->close();
    
    // Actualizar la asignación
    $update_query = "UPDATE asignaciones SET curso_id = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ii", $curso_id, $asignacion_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: asignacion_aulas.php?success=1&mensaje=Asignación actualizada correctamente");
    } else {
        $stmt->close();
        header("Location: asignacion_aulas.php?error=1&mensaje=Error al actualizar la asignación");
    }
    exit;
}

header("Location: asignacion_aulas.php");
?>