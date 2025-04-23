<?php
session_start();
include 'config.php';
include 'includes/security.php';

// Capturar los datos correctamente
$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$rol = isset($_POST['rol']) ? trim($_POST['rol']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

// Verificar que los campos no estén vacíos
if (empty($usuario) || empty($nombre) || empty($apellido) || empty($email) || empty($rol) || empty($password) || empty($confirm_password)) {
    die("Error: Todos los campos son obligatorios.");
}

// Validar que el rol sea válido
$roles_validos = ['administrador', 'mantenimiento', 'auxiliar'];
if (!in_array($rol, $roles_validos)) {
    die("Error: Rol no válido.");
}

// Validar que las contraseñas coincidan
if ($password !== $confirm_password) {
    die("Error: Las contraseñas no coinciden.");
}

// Encriptar la contraseña antes de almacenarla
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Insertar en la base de datos
$sql = "INSERT INTO usuarios (usuario, nombre, apellido, email, rol, password) VALUES (?, ?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ssssss", $usuario, $nombre, $apellido, $email, $rol, $password_hash);
    if ($stmt->execute()) {
        header("Location: login.php?success=1&mensaje=Usuario registrado correctamente");
        exit();
    } else {
        die("Error al registrar usuario.");
    }
    $stmt->close();
} else {
    die("Error en la preparación de la consulta.");
}

$conn->close();
?>
