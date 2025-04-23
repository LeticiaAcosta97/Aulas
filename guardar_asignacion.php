<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aula_id = intval($_POST['aula_id']);
    $curso_id = intval($_POST['curso_id']);
    
    // Obtener el turno del curso que se quiere asignar
    $get_curso_turno = "SELECT c.turno, c.nombre as curso, car.nombre as carrera 
                        FROM cursos c 
                        JOIN carreras car ON c.carrera_id = car.id 
                        WHERE c.id = ?";
    $stmt = $conn->prepare($get_curso_turno);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $curso_actual = $result->fetch_assoc();
    $stmt->close();
    
    // Verificar si el curso ya está asignado a otra aula
    $check_curso_query = "SELECT a.*, aul.numero as numero_aula 
                         FROM asignaciones a
                         JOIN aulas aul ON a.aula_id = aul.id
                         JOIN cursos c ON a.curso_id = c.id
                         WHERE c.id = ?";
    $stmt = $conn->prepare($check_curso_query);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $asignacion = $result->fetch_assoc();
        $stmt->close();
        header("Location: asignacion_aulas.php?error=1&mensaje=¡Atención! Este curso ya está asignado al aula " . 
               $asignacion['numero_aula']);
        exit;
    }
    $stmt->close();
    
    // Verificar si el aula ya tiene una asignación en el mismo turno
    $check_aula_query = "SELECT a.*, c.nombre as curso, car.nombre as carrera, c.turno 
                        FROM asignaciones a
                        JOIN cursos c ON a.curso_id = c.id
                        JOIN carreras car ON c.carrera_id = car.id
                        WHERE a.aula_id = ? AND c.turno = ?";
    $stmt = $conn->prepare($check_aula_query);
    $stmt->bind_param("is", $aula_id, $curso_actual['turno']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $asignacion = $result->fetch_assoc();
        $stmt->close();
        header("Location: asignacion_aulas.php?error=1&mensaje=El aula ya tiene asignado el curso " . 
               urlencode($asignacion['curso']) . " en el turno " . $asignacion['turno']);
        exit;
    }
    $stmt->close();
    
    // Si no existe asignación en ese turno, proceder con la inserción
    $insert_query = "INSERT INTO asignaciones (aula_id, curso_id, fecha_asignacion) 
                    VALUES (?, ?, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ii", $aula_id, $curso_id);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: asignacion_aulas.php?success=1&mensaje=Curso asignado correctamente");
    } else {
        $stmt->close();
        header("Location: asignacion_aulas.php?error=2&mensaje=Error al guardar la asignación");
    }
    exit;
}

header("Location: asignacion_aulas.php");
?>
