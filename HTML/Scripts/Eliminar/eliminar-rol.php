<?php
	include "../variables.php";
    if (!$_SESSION["login"]) {
        header('Location: ../../index.php');
		exit;
    }
	$id_rol = $_POST['id_rol'];
	$sql_delrol = "delete from roles where id = $id_rol";
    if ($conexion->query($sql_delrol) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "El Rol se eliminó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "El Rol no se pudo eliminar, quizás tenga un usuario asociado.";
	}
	
	header("Location: ../../index.php");
	exit;
?>