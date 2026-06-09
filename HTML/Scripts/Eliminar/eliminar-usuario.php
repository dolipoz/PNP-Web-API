<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$usuario = $_POST['usuario'];
	$sql_deluser = "delete from usuarios where usuario = $usuario";
    if ($conexion->query($sql_deluser) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "Usuario se eliminó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "Usuario no se pudo eliminar.";
	}
	
	header("Location: ../../index.php");
?>