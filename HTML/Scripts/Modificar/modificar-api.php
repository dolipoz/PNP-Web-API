<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";


	$id = isset($_POST['id_api']) ? $_POST['id_api'] : null;
	$tenant = isset($_POST['tenant']) ? $_POST['tenant'] : null;
	$sitio = isset($_POST['sitio']) ? $_POST['sitio'] : null;
	$id_cliente = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : null;

	$sql_mod_api="update api set 
		tenant = '$tenant',
		sitio = '$sitio',
		id_cliente = '$id_cliente' 
		where id = '$id'
	";
	
    if ($conexion->query($sql_mod_api) == True) {
		$filas = mysqli_affected_rows($conexion);
		if ($filas > 0) {
			echo "API modificada.";
			$_SESSION["correcto"] = True;
			$_SESSION["info"] = "La API se modificó correctamente.";
		} else {
			$_SESSION["error"] = True;
			$_SESSION["info"] = "No se encontraron cambios.";
		}
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "La API no se encuentra.";
	}

	header("Location: ../../index.php");	
?>

