<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Daniel Olivares Pozo">
    <link rel="stylesheet" href="CSS/general.css">
    <title>Sharepoint Powershell API</title>
</head>
<body>
    <!-- Iniciamos sesión por cada usuario para usar variables globales -->
    <?php
        session_start();
        $_SESSION["login"] = false;
    ?>
    <div id="ventana">
        <nav>
            <!-- Navegador general para los enlaces -->
            <ul id="pestanias">
                <?php if (!$_SESSION["login"]) echo '<li id="login">Iniciar Sesión</li>' ?>
                <?php if ($_SESSION["login"]) echo '<li id="signup">Crear Usuario</li>' ?>
            </ul>
        </nav>

