<?php
	include "../variables.php";
    if (!$_SESSION["login"]) {
        header('Location: ../../index.php');
		exit;
    }
	$tenant = $_POST['tenant'];
	$sitio = $_POST['sitio'];
	$id_cliente = $_POST['id_cliente'];

	$sql_add_api="insert into api (tenant,sitio,id_cliente) values (
		'$tenant',
		'$sitio',
		'$id_cliente'
	)";

    if ($conexion->query($sql_add_api) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "La API se añadió correctamente, inserte un certificado para utilizarlo.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "La API no se pudo añadir.";
	}
	
	header("Location: ../../index.php");
	exit;
?>
