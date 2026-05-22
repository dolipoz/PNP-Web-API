. \scripts\importaciones.ps1

Write-Host "Starting worker..."
Write-Host "Threads: $MaxThreads"
for ($index=1; $index -le $MaxThreads; $index++) {
    try {
        ejecutarquery("insert into trabajos (nombre_contenedor,id_trabajo,trabajo,estado) values ('$nombre_contenedor',$index,'mostrar fecha y hora','running');")
    }   catch {
        continue
    }
    Start-ThreadJob -ArgumentList $index {
        param($index)
        . \scripts\importaciones.ps1
        while ($true) {
            try {
                $datetime = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
                ejecutarquery("update trabajos set estado = 'running', salida = '$datetime' where nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
            }
            catch {
                ejecutarquery("update trabajos set estado = 'error' where nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
            }
            Write-Output $nombre_contenedor
            Start-Sleep -Seconds 5
        }
    }
}
while ($true) {
    Get-Job | Format-List -Property *
    Start-Sleep -Seconds 10
}