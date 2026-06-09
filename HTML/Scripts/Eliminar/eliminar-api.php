<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$id = $_POST['id'];
	$sql_delapi = "delete from api where id = $id";
    if ($conexion->query($sql_delapi) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "API se eliminó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "API no se pudo eliminar.";
	}
	header("Location: ../../index.php");
?>