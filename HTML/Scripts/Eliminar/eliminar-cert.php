<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$nombre = $_POST['nombre'];

	$comando = '{"script": "eliminar-cert.ps1", "parametros": ["'.$nombre.'"]}';
	$sql_delcert = "insert into tareas (comando, estado) values ('$comando', 'pendiente')";
    if ($conexion->query($sql_delcert) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "El Certificado se eliminó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "El Certificado no se pudo eliminar.";
	}
	
	header("Location: ../../index.php");
?>