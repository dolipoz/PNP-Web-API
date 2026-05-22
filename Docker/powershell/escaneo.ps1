$MaxThreads = 4
$SQLhost = "servicio-mysql"
$SQLdb = "powershell_api"
$SQLuser = "pwsh"
$SQLpass = "12345678"
$nombre_contenedor = $env:HOSTNAME
function ejecutarquery {
    param(
        [string]$query
    )
    $query_limpia = $query.Replace('"', '\"')
    $cmd = @(
        "-h", $SQLhost,
        "-u", $SQLuser,
        "-p$SQLpass",
        "-D", $SQLdb,
        "-N",
        "-e", $query_limpia
    )
    & mysql @cmd
}

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
        while ($true) {
            try {
                $datetime = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
                Write-Host "[Thread $index] Running at $datetime"
                $resultado = ejecutarquery("SELECT NOW();")
                Write-Host "[Thread $index] MySQL OK: $resultado"
            }
            catch {
                Write-Host "[Thread $index] ERROR"
                Write-Host $_
            }
            Start-Sleep -Seconds 5
        }
    }
}
while ($true) {
    Get-Job | Where-Object {
        $_.State -eq 'Running'
    }
    Start-Sleep -Seconds 10
}