<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Crear Cuenta</h2>
       <form action="procesar_registro.php" method="POST">
    <label for="usuario">Nombre de Usuario:</label>
    <input type="text" id="usuario" name="usuario" required placeholder="Ingrese su usuario">
    
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required placeholder="Ingrese su nombre">

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" required placeholder="Ingrese su apellido">

    <label for="email">Correo Electrónico:</label>
    <input type="email" id="email" name="email" required placeholder="Ingrese su email">

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required placeholder="Ingrese su contraseña">

    <label for="confirm_password">Confirmar Contraseña:</label>
    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirme su contraseña">

    <button type="submit">Registrarse</button>
</form>

