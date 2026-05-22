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

            ejecutarquery("update trabajos set nombre_contenedor = '$nombre_contenedor', id_trabajo = $index, estado = 'running' where estado = 'pendiente' and nombre_contenedor is null;")
            ejecutarquery("update trabajos set estado = 'running', salida = current_timestamp where nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
            ejecutarquery("update trabajos set estado = 'Completado', f_finalizacion = current_timestamp where nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
        }
    }
}

# Bucle que muestra los trabajos que están en ejecución
while ($true) {
    Get-Job | Format-List -Property *
    Start-Sleep -Seconds 10
}