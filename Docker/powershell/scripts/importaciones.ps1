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