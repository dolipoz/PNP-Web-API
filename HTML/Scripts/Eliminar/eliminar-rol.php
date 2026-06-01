<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$id = $_POST['id'];
	$sql_delrol = "delete from roles where id = $id";
    if ($conexion->query($sql_delrol) == True) {
        echo "Rol eliminada.";
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "El Rol se eliminó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "El Rol no se pudo eliminar.";
	}
	header("Location: ../../index.php");
?>