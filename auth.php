<?php
session_start();

function require_role($roles) {
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], (array)$roles)) {
        header("Location: acceso_denegado.php");
        exit;
    }
}
?>