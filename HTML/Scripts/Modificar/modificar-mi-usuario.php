<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

    $usuario = $_POST['usuario'];
	$correo = $_POST['correo'];
	$nombre = $_POST['nombre'];
	$apellidos = $_POST['apellidos'];
	$activo = isset($_POST['activo']) ? 1 : 0;
	$id_rol = $_POST['id_rol'];

	$sql_mod_user="update usuarios set ";
	if (isset($_POST['clave'])) {
		$q_pass="select clave from usuarios where usuario = '$usuario'";
		$r_pass=mysqli_query($conexion,$q_pass);
		$clave_correcta = mysqli_fetch_assoc($r_pass)['clave'];
		// Comprobamos si se ha escrito otra clave diferente para cambiarla, se ha dejado igual o se ha reescrito pero como string sin hashear
		if (!password_verify($_POST['clave'], $clave_correcta)) {
			$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
			$sql_mod_user=$sql_mod_user." clave = '$clave', ";
		}
		
	}

	$sql_mod_user=$sql_mod_user."
		correo = '$correo',
		nombre = '$nombre',
		apellidos = '$apellidos',
		activo = '$activo',
		id_rol = '$id_rol'
		where usuario = '$usuario'
	";
	
    if ($conexion->query($sql_mod_user) == True) {
        echo "Usuario modificado.";
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "Su usuario se modificó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "Su usuario no se pudo modificar.";
	}

	header("Location: ../../index.php");
?>
