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
        // Importamos las variables e iniciamos la sesión del navegador
        include "Scripts/variables.php";
        if ($_SESSION['alerta']) {
            // Si 'alerta' es true, se mostrará el mensaje de aviso de alerta
            echo '<script>alert("'.$_SESSION['info'].'")</script>';
            $_SESSION['alerta'] = False;
        }
        if ($_SESSION['error']) {
            // Si 'error' es true, se mostrará el mensaje de error
            echo '<script>alert("'.$_SESSION['info'].'")</script>';
            $_SESSION['error'] = False;
        }
        if ($_SESSION['correcto']) {
            // Si 'correcto' es true, se mostrará el mensaje de información
            echo '<script>alert("'.$_SESSION['info'].'")</script>';
            $_SESSION['correcto'] = False;
        }
        include "Scripts/funciones.php";
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
<header>
    <?php
        include "Scripts/conectar-db.php";
    ?>
</header>