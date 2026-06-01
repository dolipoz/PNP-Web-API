<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$tenant = $_POST['tenant'];
	$sitio = $_POST['sitio'];
	$id_cliente = $_POST['id_cliente'];

	$sql_add_api="insert into api (tenant,sitio,id_cliente) values (
		'$tenant',
		'$sitio',
		'$id_cliente'
	)";

    if ($conexion->query($sql_add_api) == True) {
        echo "API añadido.";
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "La API se añadió correctamente, inserte un certificado para utilizarlo.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "La API no se pudo añadir.";
	}

	header("Location: ../../index.php");



?>
