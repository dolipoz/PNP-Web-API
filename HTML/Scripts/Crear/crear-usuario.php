<?php
	include "../variables.php";
	include "../funciones.php";
	include "../conectar-db.php";

	$usuario = $_POST['usuario'];
	$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
	$correo = $_POST['correo'];
	$nombre = $_POST['nombre'];
	$apellidos = $_POST['apellidos'];
	$activo = isset($_POST['activo']) ? 1 : 0;
	$id_rol = $_POST['id_rol'];

	$sql_signup = "insert into usuarios (usuario,clave,correo,nombre,apellidos,activo,id_rol) values (
		'$usuario',
		'$clave',
		'$correo',
		'$nombre',
		'$apellidos',
		$activo,
		$id_rol
	)";
    if ($conexion->query($sql_signup) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "Usuario se añadió correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "Usuario no se pudo añadir.";
	}
	header("Location: ../../index.php");



?>
