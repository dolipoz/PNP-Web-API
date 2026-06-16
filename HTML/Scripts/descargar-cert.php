<?php
	include "variables.php";
    if (!$_SESSION["login"]) {
        header('Location: ../index.php');
        exit;
    }
    // Comprobamos que la variable certificado no está vacía y la buscamos en la base de datos para descargar el contenido
    if (isset($_GET['nombre'])) {
        $nombre = $_GET['nombre'];
        $q_cert = "select contenido from certificados where nombre = '$nombre'";
        $certificados = mysqli_query($conexion,$q_cert);
        $cert = mysqli_fetch_assoc($certificados);
        if ($cert) {
            // Si encuentra el certifiado obtiene el contenido del certificado para convertirlo en un archivo descargable
            header('Content-Type: application/x-pem-file');
            header('Content-Disposition: attachment; filename="'.$nombre.'.cer"');
            header('Content-Length: '.strlen($cert['contenido']));
            echo $cert['contenido'];
            exit;
        } else {
            $_SESSION['error'] = true;
            $_SESSION['info'] = 'No se encontró el certificado en la base de datos.';
            header("Location: ../index.php");
            exit;
        }
    } else {
        $_SESSION['error'] = true;
        $_SESSION['info'] = 'El campo de nombre de certificado está vacío.';
        header("Location: ../index.php");
        exit;
    }
?>