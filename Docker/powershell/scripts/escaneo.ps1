# Importamos las variables y funciones
. \scripts\importaciones.ps1

Write-Host "Starting worker..."
Write-Host "Threads: $MaxThreads"
# Creamos los jobs que se ejecutan en segundo plano actualizando la info de la base de datos
for ($index=1; $index -le $MaxThreads; $index++) {
    ejecutarquery("insert into trabajos (nombre_contenedor,id_trabajo,trabajo,estado) values ('$nombre_contenedor',$index,'mostrar fecha y hora','running');")
    # Usamos el módulo de ThreadJob para gestionar mejor los jobs
    Start-ThreadJob -ArgumentList $index {
        param($index)
        # Importamos las variables y funciones dentro de cada job ya que no recoge las variables ni funciones del script base
        . \scripts\importaciones.ps1
        while ($true) {
            $datetime = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
            ejecutarquery("update trabajos set estado = 'running', salida = '$datetime' where nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
            Start-Sleep -Seconds 10
        }
    }
}

# Bucle que muestra los trabajos que están en ejecución
while ($true) {
    Get-Job | Format-List -Property *
    Start-Sleep -Seconds 10
}