<?php
    include "variables.php";
    include "funciones.php";
    include "conectar-db.php";

    header('Content-Type: application/json');
    $q_trabajos = "
        select
            id,
            id_api,
            nombre_contenedor,
            id_trabajo,
            trabajo,
            estado,
            salida,
            error,
            progreso,
            f_finalizacion
        from trabajos
    ";

    // Ponemos los contadores de los trabajos a 0
    $pendientes = $corriendo = $completados = $erroneos = 0;

    // Buscamos todos los trabajos en la base de datos para contarlos y almacenar en el json los que estén en ejecución o pendientes
    $lista_trabajos = [];
    $trabajos = mysqli_query($conexion, $q_trabajos);
    while ($trabajo = mysqli_fetch_assoc($trabajos)) {
        if ($trabajo['estado'] == 'pendiente') {
            $lista_trabajos[] = $trabajo;
            $pendientes += 1;
        } elseif ($trabajo['estado'] == 'corriendo') {
            $lista_trabajos[] = $trabajo;
            $corriendo += 1;
        } elseif ($trabajo['estado'] == 'completado') {
            $completados += 1;
        } elseif ($trabajo['estado'] == 'fallido') {
            $erroneos += 1;
        }
    }
    // Creamos el json con los contadores y lo enviamos para que el script pueda sacar los datos
    $json = [
        "pendientes" => $pendientes,
        "corriendo" => $corriendo,
        "completados" => $completados,
        "erroneos" => $erroneos,
        "trabajos" => $lista_trabajos
    ];
    echo json_encode($json);

?>

