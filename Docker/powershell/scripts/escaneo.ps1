# Importamos las variables y funciones
. \scripts\importaciones.ps1

# Creamos los jobs que se ejecutan en segundo plano actualizando la info de la base de datos
for ($index=1; $index -le $MaxHilos; $index++) {
    # Usamos el módulo de ThreadJob para gestionar mejor los jobs
    Start-ThreadJob -ArgumentList $index {
        param($index)
        # Importamos las variables y funciones dentro de cada job ya que no recoge las variables ni funciones del script base
        . \scripts\importaciones.ps1
        while ($true) {
            # Buscamos en la base de datos el primer trabajo que esté pendiente y que no haya sido asignado por otro worker
            $buscar_trabajo = ejecutarquery("select * from trabajos where estado = 'pendiente' and nombre_contenedor is null;")
            # Si lo encuentra le da nombre del worker que lo va a ejecutar
            if ($null -ne $buscar_trabajo) {
                ejecutarquery("update trabajos set nombre_contenedor = '$nombre_contenedor', id_trabajo = $index, estado = 'ejecutando' where estado = 'pendiente' and nombre_contenedor is null limit 1;")
                Start-Sleep -Seconds 1
                $empezar_trabajo = ejecutarquery("select * from trabajos where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
                try {
                    ### Aquí empieza ha realizar el trabajo
                    foreach ($fila in $empezar_trabajo) {
                        $columnas = $fila -split "`t"
                        $id = $columnas[0]
                    }
                    $tareas = (0,1,2)
                    $salto = 100 / $tareas.Length
                    foreach ($n in $tareas) {
                        $indice_elemento = [array]::IndexOf($tareas, $n) + 1
                        $progreso = [math]::round($salto * $indice_elemento)
                        ejecutarquery("update trabajos set progreso = $progreso where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
                        Start-Sleep -Seconds 5
                    }

                    ### Tras llegar el proceso a 100% se actualizará el trabajo como completado y se le pondrá fecha de finalización
                    ejecutarquery("update trabajos set estado = 'Completado', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
                } catch {
                    ejecutarquery("update trabajos set estado = 'Error', error = '$_', bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
                }
            }
            Start-Sleep -Seconds 5
        }
    }
}
# Bucle que muestra los trabajos que están en ejecución
while ($true) {
    Get-Job | Format-List -Property Id, State, Error, Output
    Start-Sleep -Seconds 5
}