<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$id = $_POST['id_usuario'];
	$correo = isset($_POST['correo']) ? $_POST['correo'] : null;
	$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
	$apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : null;

	$sql_mod_user = "update usuarios set ";
	if (isset($_POST['clave'])) {
		$q_pass = "select clave from usuarios where id = $id";
		$r_pass = mysqli_query($conexion,$q_pass);
		$clave_correcta = mysqli_fetch_assoc($r_pass)['clave'];
		// Comprobamos si se ha escrito otra clave diferente para cambiarla, se ha dejado igual o se ha reescrito pero como string sin hashear
		if (!password_verify($_POST['clave'], $clave_correcta)) {
			$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
			$sql_mod_user = $sql_mod_user." clave = '$clave', ";
		}
	}

	$sql_mod_user = $sql_mod_user."
		correo = '$correo',
		nombre = '$nombre',
		apellidos = '$apellidos'
		where id = $id
	";
	
    if ($conexion->query($sql_mod_user) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "Su usuario se modificó correctamente.";
		$_SESSION["usuario"]["correo"] = $correo;
		$_SESSION["usuario"]["nombre"] = $nombre;
		$_SESSION["usuario"]["apellidos"] = $apellidos;
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "Su usuario no se pudo modificar.";
	}

	header("Location: ../../index.php");
?>
