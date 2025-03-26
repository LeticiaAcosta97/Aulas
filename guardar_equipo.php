<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $cantidad = intval($_POST['cantidad']);
    $estado = $conn->real_escape_string($_POST['estado']);

    if ($id > 0) {
        // Actualizar equipo existente
        $sql = "UPDATE equipos SET nombre='$nombre', cantidad='$cantidad', estado='$estado' WHERE id='$id'";
        if ($conn->query($sql)) {
            header("Location: asignacion_aulas.php?mensaje=Equipo actualizado");
        } else {
            echo "Error al actualizar equipo: " . $conn->error;
        }
    } else {
        echo "Error: ID de equipo no válido.";
    }
} else {
    echo "Acceso denegado.";
}
?>
