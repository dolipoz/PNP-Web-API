<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$id_cert = $_POST['id_cert'];
	$id_api = $_POST['id_api'];

	$sql_asoc_api="insert into api_certificados (id_api,id_certificado) values (
		$id_api,
		$id_cert		
	)";

    if ($conexion->query($sql_asoc_api) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "El certificado se asoció correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "El certificado no se pudo asociar.";
	}
	
	header("Location: ../../index.php");
?>
