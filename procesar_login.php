<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];

    // Primero verificamos si el usuario existe
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verificación temporal sin hash para 'admin'
        if ($usuario === 'admin' && $password === 'adm123') {
            $_SESSION["nombre"] = $row["nombre"];
            header("Location: dashboard.php");
            exit();
        }
    }
    
    $_SESSION['error'] = "Usuario o contraseña incorrecta";
    header("Location: login.php");
    exit();
}
?>
