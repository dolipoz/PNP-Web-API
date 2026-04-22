<?php
    // Función para conexión de Mysql sin seleccionar la base de datos
    function conectarDB($host, $usuario, $clave) {
        // Creamos una función con un try para evitar que php corte la página por fallo de conexión
        try {
            $c = new mysqli($host, $usuario, $clave);
            return $c;
        } catch (Exception $e) {
            return null;
        }
    }

?>