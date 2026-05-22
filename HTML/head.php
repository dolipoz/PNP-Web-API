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
    <div id="ventana">
        <nav>
            <!-- Navegador general para los enlaces -->
            <ul>
            <?php
            if (!$_SESSION["login"]) {
                echo "<li id='login' class='pestanias' style='background-color: #012456;'><a href='#' onclick='mostrarConsola(\"login\",\"consola_login\")'>Iniciar Sesión</a></li>";
            } else {
                echo "
                <li id='perfil' class='pestanias' style='background-color: #012456;'><a href='#' onclick='mostrarConsola(\"perfil\",\"consola_perfil\")'>Mi Perfil</a></li>
                <li id='logoff' class='pestanias'><a href='#' onclick='mostrarConsola(\"logoff\",\"consola_logoff\")'>Cerrar Sesión</a></li>
                ";
                if ($puede_crear_usuarios) {
                    echo "<li id='signup' class='pestanias'><a href='#' onclick='mostrarConsola(\"signup\",\"consola_signup\")'>Crear Usuario</a></li>";
                }
                if ($puede_modificar_usuarios or $puede_eliminar_usuarios) {
                    echo "<li id='gestion_usuarios' class='pestanias'><a href='#' onclick='mostrarConsola(\"gestion_usuarios\",\"consola_g_usuarios\")'>Gestionar Usuarios</a></li>";
                }
                if ($puede_crear_api) {
                    echo "<li id='addapi' class='pestanias'><a href='#' onclick='mostrarConsola(\"addapi\",\"consola_addapi\")'>Crear API</a></li>";
                }
                if ($puede_modificar_api or $puede_eliminar_api) {
                    echo "<li id='gestion_api' class='pestanias'><a href='#' onclick='mostrarConsola(\"gestion_api\",\"consola_g_api\")'>Gestionar APIs</a></li>";
                }
                if ($puede_crear_certificados) {
                    echo "<li id='addcert' class='pestanias'><a href='#' onclick='mostrarConsola(\"addcert\",\"consola_addcert\")'>Crear Certificado</a></li>";
                }
                if ($puede_modificar_certificados or $puede_eliminar_certificados) {
                    echo "<li id='gestion_cert' class='pestanias'><a href='#' onclick='mostrarConsola(\"gestion_cert\",\"consola_g_cert\")'>Gestionar Certificados</a></li>";
                }
            }
            ?>
            </ul>
        </nav>
<header>
    <?php
        include "Scripts/conectar-db.php";
    ?>
</header>