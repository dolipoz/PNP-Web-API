<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Variables globales para cada sesión de cliente Web
    session_start();

    $MaxTamaArchivos = 10;
    $MaxMB = $MaxTamaArchivos*1024*1024;
    $extensionesValidas = array("csv","CSV");

    $Q_roles = "select id,rol from roles";
    $Q_usuarios = "select * from usuarios";
    $Q_apis = "select * from api";
    $Q_certs = "select * from certificados";
    $Q_tareas = "select * from tareas";
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
                13 => ['13' => false], 14 => ['14' => false], 15 => ['15' => false],
                // Permisos de ver, modificar o eliminar tareas
                16 => ['16' => false], 17 => ['17' => false], 18 => ['18' => false]
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
    $puede_crear_tareas = array_values($_SESSION['usuario']['permisos'][16])[0];
    $puede_modificar_tareas = array_values($_SESSION['usuario']['permisos'][17])[0];
    $puede_eliminar_tareas = array_values($_SESSION['usuario']['permisos'][18])[0];

?>