<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$nombre = $_POST['nombre'];
	$sql_delcert = "delete from certificados where nombre = '$nombre'";
    if ($conexion->query($sql_delcert) == True) {
        echo "Certificado eliminado.";
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "El Certificado se eliminó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "El Certificado no se pudo eliminar.";
	}
	header("Location: ../../index.php");
?>