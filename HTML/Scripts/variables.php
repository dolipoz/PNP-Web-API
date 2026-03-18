<?php
    // Variables globales para cada sesión de cliente Web
    session_start();

    if (!isset($_SESSION["administrador"])) {
        // Variable que determina si el usuario que inició sesión es administrador o no
        $_SESSION["administrador"] = False;
    }
    if (!isset($_SESSION["login"])) {
        // Variable que determina si se ha iniciado sesión
        $_SESSION["login"] = False;
    }
    if (!isset($_SESSION["conexion"])) {
        // Variable que determina si se ha establecido conexión con la base de datos
        $_SESSION["conexion"] = False;
    }
    if (!isset($_SESSION["error"])) {
        // Variable que determina si ha algún error de ejecución dentro de la sesión
        $_SESSION["error"] = False;
    }
    if (!isset($_SESSION["alerta"])) {
        // Variable que determina si se ha detectado algún fallo menor en la sesión
        $_SESSION["alerta"] = False;
    }
    if (!isset($_SESSION["info"])) {
        // Variable que determina los detalles del error dentro de la sesión
        $_SESSION["info"] = False;
    }
    if (!isset($_SESSION["correcto"])) {
        
        $_SESSION["correcto"] = False;
    }
    if (!isset($_SESSION["busqueda"])) {
        $_SESSION["busqueda"] = 'select * from productos order by fecha_in';
    }
    if (!isset($_SESSION["secreto"])) {
        $_SESSION["secreto"] = '';
    }
?>