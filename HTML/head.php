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
        $puede_crear_usuarios = array_values($_SESSION['usuario']['permisos'][1])[0];
        $puede_modificar_usuarios = array_values($_SESSION['usuario']['permisos'][2])[0];
        $puede_eliminar_usuarios = array_values($_SESSION['usuario']['permisos'][3])[0];
        $puede_crear_roles = array_values($_SESSION['usuario']['permisos'][4])[0];
        $puede_modificar_roles = array_values($_SESSION['usuario']['permisos'][5])[0];
        $puede_eliminar_roles = array_values($_SESSION['usuario']['permisos'][6])[0];
        $puede_crear_permisos = array_values($_SESSION['usuario']['permisos'][7])[0];
        $puede_modificar_permisos = array_values($_SESSION['usuario']['permisos'][8])[0];
        $puede_eliminar_permisos = array_values($_SESSION['usuario']['permisos'][9])[0];
        $puede_crear_api = array_values($_SESSION['usuario']['permisos'][10])[0];
        $puede_modificar_api = array_values($_SESSION['usuario']['permisos'][11])[0];
        $puede_eliminar_api = array_values($_SESSION['usuario']['permisos'][12])[0];
        $puede_crear_certificados = array_values($_SESSION['usuario']['permisos'][13])[0];
        $puede_modificar_certificados = array_values($_SESSION['usuario']['permisos'][14])[0];
        $puede_eliminar_certificados = array_values($_SESSION['usuario']['permisos'][15])[0];
    ?>
<header>
    <?php
        include "Scripts/conectar-db.php";
    ?>
</header>