<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Daniel Olivares Pozo">
    <link rel="stylesheet" href="CSS/general.css">
    <link rel="shortcut icon" href="Multimedia/PowerShell_icon.png" type="image/x-icon">
    <title>Sharepoint Powershell API</title>
</head>
<body>
    <!-- Iniciamos sesión por cada usuario para usar variables globales -->
    <?php
        session_start();
        $_SESSION["login"] = true;
    ?>
    <div id="ventana">
        <nav>
            <!-- Navegador general para los enlaces -->
            <ul>
                <?php if (!$_SESSION["login"]) echo '<li id="login" class="pestanias"><a href="">Iniciar Sesión</a></li>' ?>
                <?php if ($_SESSION["login"]) echo '<li id="signup" class="pestanias"><a href="">Crear Usuario</a></li>' ?>
                <?php if ($_SESSION["login"]) echo '<li id="logoff" class="pestanias"><a href="">Cerrar Sesión</a></li>' ?>
            </ul>
        </nav>