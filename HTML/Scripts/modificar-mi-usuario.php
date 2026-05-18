<?php
	include "variables.php";
	include "funciones.php";
	include "conectar-db.php";

	$usuario = $_SESSION['usuario']['usuario'];
	$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
	$correo = $_POST['correo'];
	$nombre = $_POST['nombre'];
	$apellidos = $_POST['apellidos'];

	$sql_mod_user="update usuarios set 
		clave = '$clave',
		correo = '$correo',
		nombre = '$nombre',
		apellidos = '$apellidos'
		where usuario = '$usuario'
	)";

    if ($conexion->query($sql_mod_user) == True) {
        echo "Usuario modificado.";
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "Su usuario se modificó correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "Su usuario no se pudo modificar.";
	}

	header("Location: ../index.php");



?>
