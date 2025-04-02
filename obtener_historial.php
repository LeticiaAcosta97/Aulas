<?php
include "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Obtener información del equipo
    $stmt = $conn->prepare("SELECT descripcion, periodo_mantenimiento FROM equipos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc();

    if ($equipo) {
        // Asegurar que los valores no sean nulos
        $descripcion = $equipo['descripcion'] ?? '';
        $periodo = $equipo['periodo_mantenimiento'] ?? 0;
        
        echo "<h6>Equipo: " . htmlspecialchars($descripcion) . "</h6>";
        echo "<p>Período de mantenimiento: " . htmlspecialchars((string)$periodo) . " días</p>";

        // Resto del código del historial
        $stmt = $conn->prepare("SELECT hm.fecha_mantenimiento, hm.observaciones, e.observaciones as observaciones_generales 
                               FROM historial_mantenimiento hm
                               JOIN equipos e ON hm.equipo_id = e.id
                               WHERE hm.equipo_id = ? 
                               ORDER BY hm.fecha_mantenimiento DESC");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<table class='table table-striped'>";
        echo "<thead><tr>
                <th>Fecha</th>
                <th>Observaciones del Mantenimiento</th>
                <th>Observaciones Generales</th>
              </tr></thead><tbody>";

        while ($row = $result->fetch_assoc()) {
            $fecha = $row['fecha_mantenimiento'] ?? '';
            $obs_mant = $row['observaciones'] ?? '';
            $obs_gen = $row['observaciones_generales'] ?? '';
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fecha) . "</td>";
            echo "<td>" . htmlspecialchars($obs_mant) . "</td>";
            echo "<td>" . htmlspecialchars($obs_gen) . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<p class='text-danger'>Equipo no encontrado</p>";
    }
}
?>