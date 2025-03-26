<?php
include "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Obtener información del equipo con período de mantenimiento
    $stmt = $conn->prepare("SELECT descripcion, periodo_mantenimiento FROM equipos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $equipo = $stmt->get_result()->fetch_assoc();

    echo "<div class='mb-3'>";
    echo "<h6>Equipo: " . htmlspecialchars($equipo['descripcion']) . "</h6>";
    echo "<p class='text-muted'>Período de mantenimiento: " . htmlspecialchars($equipo['periodo_mantenimiento']) . " días</p>";
    echo "</div>";

    // Obtener historial de mantenimiento con observaciones
    $stmt = $conn->prepare("SELECT hm.fecha_mantenimiento, hm.observaciones, e.observaciones as observaciones_equipo
                           FROM historial_mantenimiento hm
                           JOIN equipos e ON hm.equipo_id = e.id
                           WHERE hm.equipo_id = ? 
                           ORDER BY hm.fecha_mantenimiento DESC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='table-responsive'>";
    echo "<table class='table table-striped'>";
    echo "<thead><tr>
            <th>Fecha</th>
            <th>Observaciones del Mantenimiento</th>
            <th>Observaciones Generales</th>
          </tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['fecha_mantenimiento']) . "</td>";
        echo "<td>" . htmlspecialchars($row['observaciones']) . "</td>";
        echo "<td>" . htmlspecialchars($row['observaciones_equipo']) . "</td>";
        echo "</tr>";
    }

    echo "</tbody></table></div>";
}
?>