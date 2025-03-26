<?php
include("config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $aula_id = intval($_POST['aula_id']);
    $curso_id = intval($_POST['curso_id']);
    
    // Verificar si el curso ya está asignado a otra aula
    $check_curso_query = "SELECT a.*, aul.numero as numero_aula, c.nombre as curso, car.nombre as carrera, c.turno 
                         FROM asignaciones a
                         JOIN aulas aul ON a.aula_id = aul.id
                         JOIN cursos c ON a.curso_id = c.id
                         JOIN carreras car ON c.carrera_id = car.id
                         WHERE c.id = ?";
    $stmt = $conn->prepare($check_curso_query);
    $stmt->bind_param("i", $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $asignacion = $result->fetch_assoc();
        header("Location: asignacion_aulas.php?error=1&mensaje=¡Atención! El curso " . 
               urlencode($asignacion['curso']) . " de " . urlencode($asignacion['carrera']) . 
               " (Turno: " . $asignacion['turno'] . ") ya está asignado al aula " . 
               $asignacion['numero_aula']);
        exit;
    }
    
    // Verificar si el aula ya tiene asignación
    $check_aula_query = "SELECT a.*, c.nombre as curso, car.nombre as carrera 
                        FROM asignaciones a
                        JOIN cursos c ON a.curso_id = c.id
                        JOIN carreras car ON c.carrera_id = car.id
                        WHERE a.aula_id = ?";
    $stmt = $conn->prepare($check_aula_query);
    $stmt->bind_param("i", $aula_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $asignacion = $result->fetch_assoc();
        header("Location: asignacion_aulas.php?error=1&mensaje=El aula ya está asignada al curso " . 
               urlencode($asignacion['curso']) . " de la carrera " . urlencode($asignacion['carrera']));
        exit;
    }
    
    // Si no existe ninguna asignación, proceder con la inserción
    $insert_query = "INSERT INTO asignaciones (aula_id, curso_id, fecha_asignacion) 
                    VALUES (?, ?, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ii", $aula_id, $curso_id);
    
    if ($stmt->execute()) {
        header("Location: asignacion_aulas.php?success=1");
    } else {
        header("Location: asignacion_aulas.php?error=2");
    }
    exit;
}

header("Location: asignacion_aulas.php");
?>
