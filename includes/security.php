<?php
function encryptPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function checkSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['nombre'])) {
        header('Location: login.php');
        exit();
    }
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function checkUserRole($required_roles) {
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], $required_roles)) {
        header("Location: login.php");
        exit();
    }
}
?>