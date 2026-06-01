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
            # Buscamos en la base de datos el primer tarea que esté pendiente y que no haya sido asignado por otro worker
            $buscar_tarea = ejecutarquery("select * from tareas where estado = 'pendiente' and nombre_contenedor is null;")
            # Si lo encuentra le da nombre del worker que lo va a ejecutar
            if ($null -ne $buscar_tarea) {
                ejecutarquery("update tareas set nombre_contenedor = '$nombre_contenedor', id_tarea = $index, estado = 'ejecutando' where estado = 'pendiente' and nombre_contenedor is null limit 1;")
                Start-Sleep -Seconds 1
                $empezar_tarea = ejecutarquery("select * from tareas where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index limit 1;")
                try {
                    ### Aquí empieza ha realizar el tarea
                    foreach ($fila in $empezar_tarea) {
                        $columnas = $fila -split "`t"
                        $tarea = $columnas[4] | ConvertFrom-Json
                        $script = $tarea.script
                        $params = @($tarea.parametros)
                        $params += "$nombre_contenedor"
                        $params += "$index"
                        & pwsh \scripts\$script $params
                    }
                    ### Tras llegar el proceso a 100% se actualizará el tarea como completado y se le pondrá fecha de finalización
                    #ejecutarquery("update tareas set estado = 'completada', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
                } catch {
                    write-error $_
                    ejecutarquery("update tareas set estado = 'fallida', error = 'error', bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
                }
            }
            Start-Sleep -Seconds 2
        }
    }
}
# Bucle que muestra los tareas que están en ejecución
while ($true) {
    $jobs = Get-Job
    write-output  "----------------------------------------------------------------------------"
    foreach ($j in $jobs) {
        write-output  "Id: ",$j.Id
        write-output  "Estado: ",$j.State
        write-output  "Error: ",$j.Error
        write-output  "Salida: ",$j.Output
    }
    write-output  "----------------------------------------------------------------------------"
    Start-Sleep -Seconds 2
}