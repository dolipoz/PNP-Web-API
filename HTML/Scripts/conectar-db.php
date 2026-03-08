<?php
    function conectarDB($host, $usuario, $clave, $db) {
        // Creamos una función con un try para evitar que php corte la página por fallo de conexión
        try {
            $c = new mysqli($host, $usuario, $clave, $db);
            return $c;
        } catch (Exception $e) {
            return null;
        }
    }

    // Creamos las variables de la base de datos
    $host="127.0.0.1";
    $db_user="root";
    $db_pass="password";
    $basedatos="powershell_api";
    // Creamos la conexión con la base de datos
    $conexion = conectarDB($host,$db_user,$db_pass,$basedatos);

    if ($conexion === null) {
        echo '<script>alert("Error al conectar con la base de datos, recarga para volver a intentarlo")</script>';
    } else {
        $_SESSION['conexion']=true;
        $conexion->select_db($basedatos);
    }
?>