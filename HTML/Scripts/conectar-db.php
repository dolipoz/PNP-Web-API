<?php
    // Creamos las variables de la base de datos, el host es el nombre del servicio desplegado con Kubectl
    $host="servicio-mysql";
    $db_user="root";
    // Usamos el Secret creado con eksctl de la contraseña de MYSQL
    $db_pass=getenv("MYSQL_ROOT_PASSWORD");
    $basedatos="powershell_api";
    // Creamos la conexión con la base de datos
    $conexion = conectarDB($host,$db_user,$db_pass);
    if ($conexion === null) {
        echo '<script>alert("Error: '.mysqli_connect_error().'\nRecargue.")</script>';
    } else {
        $conexion->select_db($basedatos);
    }
?>