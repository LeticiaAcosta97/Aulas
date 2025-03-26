<?php
session_start();
include 'config.php'; // Conexión a la base de datos

// Capturar los datos correctamente
$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

// Verificar que los campos no estén vacíos
if (empty($usuario) || empty($nombre) || empty($apellido) || empty($email) || empty($password) || empty($confirm_password)) {
    die("Error: Todos los campos son obligatorios.");
}

// Validar que las contraseñas coincidan
if ($password !== $confirm_password) {
    die("Error: Las contraseñas no coinciden.");
}

// Encriptar la contraseña antes de almacenarla
$password_hash = password_hash($password, PASSWORD_BCRYPT);

// Insertar en la base de datos (corrigiendo la consulta para incluir `usuario`)
$sql = "INSERT INTO usuarios (usuario, nombre, apellido, email, password) VALUES (?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sssss", $usuario, $nombre, $apellido, $email, $password_hash);
    if ($stmt->execute()) {
        echo "Usuario registrado correctamente.";
    } else {
        echo "Error al registrar usuario.";
    }
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta.";
}

$conn->close();
?>
