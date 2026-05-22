# Script de powershell para crear las variables y funciones globales, para los jobs
$MaxHilos = 4
$nombre_contenedor = $env:HOSTNAME

# Función para ejecutar querys de MySQL
function ejecutarquery {
    param(
        [string]$query
    )
    try {
        $salida = mysql `
            -h 'servicio-mysql' `
            -u $env:PWSH_USER `
            -p$env:PWSH_PASS `
            -D 'powershell_api' `
            -N `
            -e $query 2>&1

        if ($LASTEXITCODE -ne 0) {
            return $false
        }
        return $salida
    }
    catch {
        return $false
    }
}