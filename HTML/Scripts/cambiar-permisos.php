<?php
    include "variables.php";
    if (!$_SESSION["login"]) {
        header('Location: ../index.php');
        exit;
    }
    $script = $_POST['script'];
    $id_api = $_POST['apis'];
    $certificado = $_POST['certificado'];

    // Si no hay fichero de entrada saldrá mostrando un error
    if (isset($_FILES['csv'])) {
        // Con un select JOIN obtenemos los datos de la API y del Certificado si están asociados
        $q_api = "
            select
                a.tenant as tenant,
                a.sitio as sitio,
                a.id_cliente as id_cliente
            from api_certificados ac
            join certificados c on ac.id_certificado = c.id
            join api a on ac.id_api = a.id
            where c.nombre = '$certificado' and ac.id_api = $id_api";
        $apis = mysqli_query($conexion,$q_api);
        if ($apis and mysqli_num_rows($apis) > 0) {
            while ($api = mysqli_fetch_assoc($apis)) {
                $sitio = $api['sitio'];
                $tenant = $api['tenant'];
                $id_cliente = $api['id_cliente'];
            }
        } else {
            $_SESSION["error"] = True;
            $_SESSION["info"] = "El certificado no está asociado a esa API.";
            header('Location: ../tareas.php');
            exit;
        }

        // Sacamos la información del CSV, su nombre, tamaño y extensión
        $nombreCSV = $_FILES['csv']['name'];
        $csvsize = $_FILES['csv']['size'];
        $arrayCSV = pathinfo($nombreCSV);
        $extension = $arrayCSV['extension'];
        // Comprobamos que la extensión es la correcta y que el fichero no sea mayor del máximo MB
        if (in_array($extension,$extensionesValidas) and $csvsize < $MaxMB) {
            // Leemos el contenido del CSV
            $csv = fopen($_FILES['csv']['tmp_name'], 'r');
            $cabecera = fgetcsv($csv,0,';','"','\\');
            $datos = [];
            while (($fila = fgetcsv($csv,0,';','"','\\')) !== false) {
                $datos[] = array_combine($cabecera, $fila);
            }
            fclose($csv);
            // Lo guardamos en la variable en vez de en un fichero del servidor
            $comando = [
                "script" => $script,
                "parametros" => [$tenant,$sitio,$id_cliente,$certificado,$datos]
            ];
            $json = json_encode($comando, JSON_UNESCAPED_UNICODE);
            // Insertamos los datos necesarios para crear un tarea que se procese por Powershell con los datos del JSON
            // Al ser una acción que cambia permisos se requiere del bloqueo para que solo se pueda ejercer uno a la vez por sitio 
            $q_cambiar_permisos = "
                insert into tareas ( id_api, comando, estado, bloqueo) values (
                '$id_api',
                '$json',
                'pendiente',
                1)";
            if ($conexion->query($q_cambiar_permisos) == True) {
                $_SESSION['correcto'] = True;
                $_SESSION["info"] = "Tarea en proceso, vea las tareas para seguir el estado.";
            } else {
                $_SESSION["error"] = True;
                $_SESSION["info"] = "No se pudo ejecutar la tarea.";
            }
        } else {
            $_SESSION["error"] = True;
            $_SESSION["info"] = "El tamaño o la extensión no son correctas.";
        }
    } else {
        $_SESSION["error"] = True;
        $_SESSION["info"] = "No se recibió ningún fichero. : ".$_FILES['csv']['name'];
    }

    header('Location: ../tareas.php');
    exit;
?>