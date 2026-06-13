<?php
	include "../variables.php";

	$rol = $_POST['rol'];
	$descripcion = $_POST['descripcion'];

	$sql_add_rol = "insert into roles (rol, descripcion) values ( '$rol', '$descripcion' )";
    if ($conexion->query($sql_add_rol) == True) {
		$rid = $conexion->insert_id;
		$permisos = mysqli_query($conexion,$Q_permisos);
		// Recorremos los permisos que existen
		if ($permisos and mysqli_num_rows($permisos) > 0) {
			while ($permiso = mysqli_fetch_assoc($permisos)) {
				$pid = $permiso['id'];
				$permiso = isset($_POST["p_$pid"]) ? 1 : 0;
				if ($permiso === 1) {
					$sql_rol_perm = "insert into roles_permisos (id_rol, id_permiso) values ( $rid, $pid )";
					if ($conexion->query($sql_rol_perm) == True) {
						$_SESSION["correcto"] = True;
						$_SESSION["info"] = "La API se añadió correctamente, inserte un certificado para utilizarlo.";
					} else {
						$_SESSION["error"] = True;
						$_SESSION["info"] = "La API no se pudo añadir.";
						mysqli_query($conexion,"delete from roles where rol = '$rol'");
						header("Location: ../../index.php");
						exit;
					}
				}
			}
		} else {
			$_SESSION["error"] = True;
			$_SESSION["info"] = "La API no se pudo añadir.";
		}

    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "La API no se pudo añadir.";
	}
	
	header("Location: ../../index.php");
?>
