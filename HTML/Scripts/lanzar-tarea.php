<?php
	include "variables.php";
	include "funciones.php";
	include "conectar-db.php";

	$script = $_POST['script'];
	$id_api = $_POST['apis'];
	$certificado = $_POST['certificado'];

    $q_apis = "select * from api where id = $id_api";
    $apis = mysqli_query($conexion, $q_apis);
    if ($apis and mysqli_num_rows($apis) > 0) {
        while ($api = mysqli_fetch_assoc($apis)) {
            $tenant = $api['tenant'];
            $sitio = $api['sitio'];
            $id_cliente = $api['id_cliente'];
        }
    }

	$comando = '{"script": "'.$script.'", "parametros": ["'.$tenant.'", "'.$sitio.'", "'.$id_cliente.'", "'.$certificado.'"]}';
	if ($script == 'resetear-permisos.ps1') {
		$sql_addtarea = "insert into tareas (id_api, comando, estado, bloqueo) values ($id_api, '$comando', 'pendiente', 1)";
	} else {
		$sql_addtarea = "insert into tareas (id_api, comando, estado) values ($id_api, '$comando', 'pendiente')";
	}
	
    if ($conexion->query($sql_addtarea) == True) {
		$_SESSION["correcto"] = True;
		$_SESSION["info"] = "Se lanzó la tarea correctamente.";
    } else {
		$_SESSION["error"] = True;
		$_SESSION["info"] = "No se pudo lanzar la tarea.";
	}
	
	header("Location: ../tareas.php");
?>