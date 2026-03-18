<?php
    include "../head.php";

    $url = $_POST['url'];
    $tenant = $_POST['tenant'];
    $tenantId = $_POST['tenantId'];
    $clientId = $_POST['clientId'];
    $clientSecret = $_POST['clientSecret'];
    $certificado = $_POST['certificado'];

    

    $command = "pwsh -NoProfile -ExecutionPolicy Bypass -File /var/www/html/Scripts/conectar-pnp.ps1 \"$url\" \"$clientId\" \"$certificado\" \"$tenant\"";
    //$command = "whoami";
    exec($command, $output, $return);

    print_r($output);


    include "footer.php";
?>