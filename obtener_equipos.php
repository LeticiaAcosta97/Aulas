<?php
include("config.php");

if (!isset($_GET['aula_id']) || !is_numeric($_GET['aula_id'])) {
    echo "ID de aula no válido";
    exit;
}

$aula_id = intval($_GET['aula_id']);
$query = "SELECT * FROM equipos WHERE aula_id = ? ORDER BY descripcion ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $aula_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="alert alert-info">No hay equipos registrados para esta aula.</div>';
} else {
    echo '<div class="row">';
    while ($row = $result->fetch_assoc()) {
        echo '<div class="equipo-item col-12 mb-3 p-3 border rounded" data-id="'.htmlspecialchars($row['id']).'">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label"><strong>Descripción:</strong></label>
                        <input type="text" class="desc-input edit-field form-control" value="'.htmlspecialchars($row['descripcion']).'" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><strong>Cantidad:</strong></label>
                        <input type="number" class="cant-input edit-field form-control" value="'.htmlspecialchars($row['cantidad']).'" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><strong>Marca:</strong></label>
                        <input type="text" class="marca-input edit-field form-control" value="'.htmlspecialchars($row['marca']).'" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><strong>Estado:</strong></label>
                        <select class="form-select estado-select" data-equipo-id="'.htmlspecialchars($row['id']).'" '.($row['estado'] == 'inactivo' ? 'style="background-color: #ffebee;"' : 'style="background-color: #e8f5e9;"').'>
                            <option value="activo" '.($row['estado'] == 'activo' ? 'selected' : '').'>Activo</option>
                            <option value="inactivo" '.($row['estado'] == 'inactivo' ? 'selected' : '').'>Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><strong>Última actualización:</strong></label>
                        <div class="estado-info">'.htmlspecialchars($row['fecha_estado']).'</div>
                    </div>
                </div>
            </div>';
    }
    echo '</div>';
}

$stmt->close();
?>
