<?php
	include "../variables.php";
    if (!$_SESSION["login"]) {
        header('Location: ../../index.php');
		exit;
    }
	
	$id_api = $_POST['id_api'];
	$sql_delapi = "delete from api where id = $id_api";
    if ($conexion->query($sql_delapi) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "API se eliminó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "API no se pudo eliminar.";
	}

	header("Location: ../../index.php");
	exit;
?>