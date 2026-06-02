<?php
    include "variables.php";
    include "funciones.php";
    include "conectar-db.php";

    $id = $_GET['id'];

    header('Content-Type: application/json');

    // Buscamos todos los apis si tienen certificados asociados
    $certificados = [];
    $q_api_cert = "
        select 
            c.nombre as nombre
        from api_certificados ac
        join certificados c on c.id = ac.id_certificado
        where ac.id_api = $id
    ";
    $api_certs = mysqli_query($conexion, $q_api_cert);
    while ($api_cert = mysqli_fetch_assoc($api_certs)) {
        $certificados[] = $api_cert['nombre'];
    }
    echo json_encode($certificados);
?>