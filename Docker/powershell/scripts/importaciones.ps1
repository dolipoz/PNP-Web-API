# Script de powershell para crear las variables y funciones globales, para los jobs
$MaxHilos = 4
$nombre_contenedor = $env:HOSTNAME

# Función para ejecutar querys de MySQL
function ejecutarquery {
    param(
        [string]$query
    )

    $salida = mysql `
        -h 'servicio-mysql' `
        -u $env:PWSH_USER `
        "--password=$env:PWSH_PASS" `
        -D 'powershell_api' `
        -N `
        -e $query
    return $salida

}