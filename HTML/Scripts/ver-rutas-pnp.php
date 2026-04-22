<?php
    include "../head.php";

    $url = $_POST['url'];
    $tenant = $_POST['tenant'];
    $clientId = $_POST['clientId'];
    $certificado = $_POST['certificado'];

    

    $command = "pwsh -NoProfile -ExecutionPolicy Bypass -File /var/www/html/Scripts/ver-pnp.ps1 \"$url\" \"$clientId\" \"$certificado\" \"$tenant\"";
    //$command = "whoami";
    exec($command, $output, $return);

    print_r($output);


    include "../footer.php";
?>