<?php
    include "variables.php";

    header('Content-Type: application/json');
    // Ponemos los contadores de los tareas a 0
    $pendientes = $ejecutando = $completadas = $fallidas = 0;

    // Buscamos todos los tareas en la base de datos para contarlos y almacenar en el json los que estén en ejecución o pendientes
    $lista_tareas = [];
    $tareas = mysqli_query($conexion, $Q_tareas);
    while ($tarea = mysqli_fetch_assoc($tareas)) {
        if ($tarea['estado'] == 'pendiente') {
            $lista_tareas[] = $tarea;
            $pendientes += 1;
        } elseif ($tarea['estado'] == 'ejecutando') {
            $lista_tareas[] = $tarea;
            $ejecutando += 1;
        } elseif ($tarea['estado'] == 'completada') {
            $completadas += 1;
        } elseif ($tarea['estado'] == 'fallida') {
            $fallidas += 1;
        }
    }
    // Creamos el json con los contadores y lo enviamos para que el script pueda sacar los datos
    $json = [
        "pendientes" => $pendientes,
        "ejecutando" => $ejecutando,
        "completadas" => $completadas,
        "fallidas" => $fallidas,
        "tareas" => $lista_tareas
    ];
    echo json_encode($json);
?>

