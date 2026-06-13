<?php
    include "variables.php";

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
    if ($api_certs and mysqli_num_rows($api_certs) > 0) {
        while ($api_cert = mysqli_fetch_assoc($api_certs)) {
            $certificados[] = $api_cert['nombre'];
        }
    }
    echo json_encode($certificados);
?>