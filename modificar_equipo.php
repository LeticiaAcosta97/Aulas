<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener datos del equipo seleccionado
    $sql = "SELECT * FROM equipos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $equipo = $resultado->fetch_assoc();
    } else {
        echo "Error: Equipo no encontrado.";
        exit;
    }
} else {
    echo "Error: ID de equipo no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Equipo</title>
</head>
<body>
    <h2>Modificar Equipo</h2>
    <form action="guardar_equipo.php" method="POST">

        <input type="hidden" name="id" value="<?php echo $equipo['id']; ?>">

        <label>Nombre del equipo:</label>
        <input type="text" name="nombre" value="<?php echo $equipo['nombre']; ?>" required><br>

        <label>Cantidad:</label>
        <input type="number" name="cantidad" value="<?php echo $equipo['cantidad']; ?>" required><br>

        <label>Estado:</label>
        <select name="estado">
            <option value="Bueno" <?php if ($equipo['estado'] == 'Bueno') echo 'selected'; ?>>Bueno</option>
            <option value="Regular" <?php if ($equipo['estado'] == 'Regular') echo 'selected'; ?>>Regular</option>
            <option value="Malo" <?php if ($equipo['estado'] == 'Malo') echo 'selected'; ?>>Malo</option>
        </select><br>

        <input type="submit" value="Guardar Cambios">
    </form>
</body>
</html>
