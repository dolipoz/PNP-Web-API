<?php
    // Variables globales para cada sesión de cliente Web
    session_start();

    if (!isset($_SESSION["usuario"])) {
        // Variable que almacena los datos del usuario que haya iniciado sesión
        $_SESSION["usuario"] = [
            'id' => 0, 'usuario' => '', 'id_rol' => 0, 'rol' => '',
            'nombre' => '', 'apellidos' => '', 'correo' => '', 'activo' => false,
            'f_creado' => null, 'ult_sesion' => null, 'permisos' => [
                // Permisos de ver, modificar o eliminar usuarios
                1 => ['1' => false], 2 => ['2' => false], 3 => ['3' => false],
                // Permisos de ver, modificar o eliminar roles
                4 => ['4' => false], 5 => ['5' => false], 6 => ['6' => false],
                // Permisos de ver, modificar o eliminar permisos
                7 => ['7' => false], 8 => ['8' => false], 9 => ['9' => false],
                // Permisos de ver, modificar o eliminar api
                10 => ['10' => false], 11 => ['11' => false], 12 => ['12' => false],
                // Permisos de ver, modificar o eliminar certificados
                13 => ['13' => false], 14 => ['14' => false], 15 => ['15' => false]
            ]
        ];
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
    if (!isset($_SESSION["querys"])) {
        
        $_SESSION["querys"] = ' where 1=1 ';
    }

?>