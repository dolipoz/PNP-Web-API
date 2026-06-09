<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$id = $_POST['id'];
	$sql_delta = "delete from tareas where id = $id";
    if ($conexion->query($sql_delta) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "La Tarea se eliminó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "La Tarea no se pudo eliminar.";
	}
	
	header("Location: ../../index.php");
?>