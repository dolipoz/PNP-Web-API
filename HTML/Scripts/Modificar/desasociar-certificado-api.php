<?php
	include "../variables.php";

	$id_api = $_POST['id_api'];
	$id_cert = $_POST['id_cert'];

	$sql_desasoc_api="delete from api_certificados where 
		id_api = $id_api and
		id_certificado = $id_cert
	";

    if ($conexion->query($sql_desasoc_api) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "El certificado se desasoció correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "El certificado no se pudo desasociar.";
	}
	
	header("Location: ../../index.php");
?>
