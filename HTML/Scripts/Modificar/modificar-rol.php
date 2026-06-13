<?php
	include "../variables.php";

	$id_rol = isset($_POST['id_rol']) ? $_POST['id_rol'] : null;
	$rol = isset($_POST['rol']) ? $_POST['rol'] : null;
	$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;

	$permisos = [];
	$permisos_rol = [];
	$cambios_permisos = false;

	$sql_permisos = mysqli_query($conexion,$Q_permisos);
	// Recorremos los permisos que existen
	if ($sql_permisos and mysqli_num_rows($sql_permisos) > 0) {
		while ($sql_permiso = mysqli_fetch_assoc($sql_permisos)) {
			$pid = (int)$sql_permiso['id'];
			$permisos[] = isset($_POST["p_$pid"]) ? [$pid,1] : [$pid,0];
		}
	}
	$q_permisos_rol = "select p.id as id from permisos p join roles_permisos rp on p.id = rp.id_permiso join roles r on r.id = rp.id_rol where rp.id_rol = $id_rol";
	$sql_permisos_rol = mysqli_query($conexion,$q_permisos_rol);
	// Recorremos los permisos del rol asociados
	if ($sql_permisos_rol and mysqli_num_rows($sql_permisos_rol) > 0) {
		while ($sql_permiso_rol = mysqli_fetch_assoc($sql_permisos_rol)) {
			$pid = (int)$sql_permiso_rol['id'];
			$permisos_rol[] = $pid;
		}
	}

	if (count($permisos) > 0) {
		foreach ($permisos as $permiso) {
			if (!in_array($permiso[0], $permisos_rol) and $permiso[1]) {
				$cambios_permisos = true;
				mysqli_query($conexion,"insert into roles_permisos (id_rol,id_permiso) values ($id_rol, {$permiso[0]})");
			} elseif (in_array($permiso[0], $permisos_rol) and !$permiso[1]) {
				$cambios_permisos = true;
				mysqli_query($conexion,"delete from roles_permisos where id_rol = $id_rol and id_permiso = {$permiso[0]}");
			}
		}
	}

	$sql_mod_rol = "update roles set 
		rol = '$rol',
		descripcion = '$descripcion' 
		where id = $id_rol
	";
	
    if ($conexion->query($sql_mod_rol) == True) {
		$filas = mysqli_affected_rows($conexion);
		if ($filas > 0 or $cambios_permisos) {
			$_SESSION["correcto"] = True;
			$_SESSION["info"] = "El rol se modificó correctamente.";
		} else {
			$_SESSION["error"] = True;
			$_SESSION["info"] = "No se encontraron cambios.";
		}
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "El rol no se encuentra.";
	}

	header("Location: ../../index.php");	
?>