<?php
    // Importamos las variables e iniciamos la sesión del navegador
    include "Scripts/variables.php";
?>
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
    ?>
    <div id='banner'>
        <div id='b_titulo'>
            <h1>Sharepoint Poweshell API</h1>
            <h3>Gestione sus APIs desde PHP</h3>
        </div>
        <div id='indice'>
            <ul>
                <li><i class='i_iconos i_indice'></i><a href="index.php">Inicio</a></li>
                <?php
                    if ($_SESSION["login"]) {
                        echo "<li><i class='i_iconos i_tareas'></i><a href='tareas.php'>Tareas</a></li>";
                    }
                ?>
            </ul>
        </div>
    </div>