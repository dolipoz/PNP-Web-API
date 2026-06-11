<?php
    // Importamos las variables e iniciamos la sesión del navegador
    include "Scripts/variables.php";
    
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
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
        include "Scripts/funciones.php";
        include "Scripts/conectar-db.php";
    ?>
    <div id='banner'>
        <div id='b_titulo'>
            <h1>Sharepoint Poweshell API</h1>
            <p>Gestione sus APIs desde PHP</p>
        </div>
        <div id='indice'>
            <ins>Índice</ins>
            <ul>
                <li><i id='i_indice'></i><a href="index.php">Inicio</a></li>
                <?php
                    if ($_SESSION["login"]) {
                        echo "<li><i id='i_tareas'></i><a href='tareas.php'>Tareas</a></li>";
                    }
                ?>
            </ul>
        </div>
    </div>