<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Daniel Olivares Pozo">
    <link rel="stylesheet" href="../CSS/general.css">
    <link rel="shortcut icon" href="../Multimedia/PowerShell_icon.png" type="image/x-icon">
    <title>Sharepoint Powershell API</title>
</head>
<body>

<?php
    // IF ELSE para que al cargar la página se genere el certificado y que cuando se pulse el botón se descargue y salga
    if (isset($_POST['certificado'])) {
        $certificado = $_POST['certificado'];
        if (file_exists($certificado)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/x-x509-ca-cert');
            header('Content-Disposition: attachment; filename='.basename($certificado));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($certificado));
            ob_clean();
            flush();
            readfile($certificado);
            header("Location: ../index.php");
        }
    } else {
        // Recojemos las variables del formulario con los datos dados por el usuario
        $pais = "ES";
        $capital = "Granada";
        $ciudad = "Atarfe";
        $orga = "IES";
        $nombre = "Daniel";
        $caduc = "365";

        // Por defecto la ruta de certificados personalizada está en la raiz
        $certDir = '/certs';
        $archivo = $certDir . "/certificate_$nombre-$ciudad.crt";
        $keyFile = $certDir . "/private_$nombre-$ciudad.key";

        if (file_exists($archivo)) {
            echo "Error, el certificado ya existe con ese nombre";
        } else {
            // Generar certificado con OpenSSL
            $cmd = "openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout $keyFile -out $archivo -subj '/C=$pais/ST=$capital/L=$ciudad/O=$orga/CN=$nombre'";
            exec($cmd, $output, $result);
            if ($result !== 0 || !file_exists($archivo)) {
                echo "Error generando certificado";
            }
        }

    }
?>


    <h1>Certificados</h1>
    <form method="post">
        <input type="text" name="certificado" id="certificado" value="<?php echo $archivo; ?>" hidden>
        <button type="submit" <?php echo "hidden"; ?>>Generar y descargar certificado</button>
    </form>

</body>
</html>
