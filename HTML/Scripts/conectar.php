<?php
    include "../head.php";

    $url = $_POST['url'];
    $tenant = $_POST['tenant'];
    $tenantId = $_POST['tenantId'];
    $clientId = $_POST['clientId'];
    $clientSecret = $_POST['clientSecret'];
    // Obtener token de acceso para PNP.powershell
    $token = generarToken($tenant, $tenantId, $clientId, $clientSecret);

    

    $command = "powershell -ExecutionPolicy Bypass -File /var/www/html/Scripts/conectar-pnp.ps1 \"$url\" \"$token\"";
    //$command = "whoami";
    exec($command, $output, $return);

    if ($return) {
        echo "<h1>Funciona</h1>";
        print_r($output);
    } else {
        echo "</h1>No funciona</h1>";
    }

    include "footer.php";
?>