<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$nombre = $_POST['certificado'];
	$pais = $_POST['pais'];
	$ciudad = $_POST['ciudad'];
	$localidad = $_POST['localidad'];
	$expira = $_POST['expira'];

	$comando = '{"script": "crear-cert.ps1", "parametros": ["'.$nombre.'", "'.$pais.'", "'.$ciudad.'", "'.$localidad.'", '.$expira.']}';
	$sql_addcert = "insert into tareas (comando, estado) values ('$comando', 'pendiente')";
    if ($conexion->query($sql_addcert) == True) {
        echo "Certificado añadido.";
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "El Certificado se añadió correctamente, descarguelo desde gestión de certificados.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "El Certificado no se pudo añadir.";
	}
	
	header("Location: ../../index.php");
?>
