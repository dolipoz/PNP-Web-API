# Script de powershell para crear las variables y funciones globales, para los jobs
$MaxHilos = 4
$nombre_contenedor = $env:HOSTNAME


$traducirPermisos = @{
    "" = "Vacio"
    "RO" = "Leer"
    "RW" = "Colaborar"
}

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

function ObtenerCarpetas {
    param (
        $csv
    )
    $cabecerasNiveles = $csv[0].PSObject.Properties.Name | Where-Object { $_ -match '^NIVEL' }
    $cabecerasPermisos = $csv[0].PSObject.Properties.Name | Where-Object { $_ -notmatch '^NIVEL' }
    # Diccionario para almacenar la estructura de carpetas con sus permisos
    $carpetas = @{}
    # Variables para controlar las celdas vacías del excel
    $digitoActualPorNivel = @{}
    $contadorHijosPorPadre = @{}

    foreach ($fila in $csv) {
        $permisosFila = foreach ($grupo in $cabecerasPermisos) {
            $fila.$grupo
        }
        $digitoMasProfundo = $null
        for ($nivel = 1; $nivel -le $cabecerasNiveles.Count; $nivel++) {
            $cabecera = $cabecerasNiveles[$nivel - 1]
            $valor = $fila.$cabecera
            if ([string]::IsNullOrWhiteSpace($valor)) { continue }
            # Obtener cadena numerica, si no hay lo deja en NULL
            if ($valor -match '^(\d+(?:\.\d+)*)') {
                $digito = $matches[1]
            } else {
                $digito = $null
            }

            if ($digito) {
                $digitoActualPorNivel[$nivel] = $digito
                # Limpiar niveles inferiores
                for ($n = $nivel + 1; $n -le $cabecerasNiveles.Count; $n++) { $digitoActualPorNivel.Remove($n) }
                $digitoMasProfundo = $digito
            } else {
                if ($nivel -eq 1) { throw "Nivel 1 sin código: $valor" }
                $padre = $digitoActualPorNivel[$nivel - 1]
                if (-not $contadorHijosPorPadre.ContainsKey($padre)) { $contadorHijosPorPadre[$padre] = 0 }
                $contadorHijosPorPadre[$padre]++
                $digito = "{0}.{1:d2}" -f $padre, $contadorHijosPorPadre[$padre]
                $digitoActualPorNivel[$nivel] = $digito
                $digitoMasProfundo = $digito
            }
        }
        if (-not $digitoMasProfundo) { continue }
        if (-not $carpetas.ContainsKey($digitoMasProfundo)) { $carpetas[$digitoMasProfundo] = @( '', '', '', '' ) }

        $Actual = $carpetas[$digitoMasProfundo]
        $Nuevo = $permisosFila
        for ($i = 0; $i -lt $Nuevo.Count; $i++) {
            if ([string]::IsNullOrWhiteSpace($Nuevo[$i])) { continue }
            if ($Actual[$i] -eq 'RW') { continue }
            $Actual[$i] = $Nuevo[$i]
        }
        $carpetas[$digitoMasProfundo] = $Actual
    }
    # Ponemos los permisos de Solo Lectura a las carpetas padre para permitir acceso
    foreach ($carpeta in @($carpetas.Keys)) {
        $permisos = $carpetas[$carpeta]
        $partes = $carpeta -split '\.'
        if ($partes.Count -le 1) { continue }
        for ($i = 1; $i -lt $partes.Count; $i++) {
            $padre = ($partes[0..($i-1)] -join '.')
            if (-not $carpetas.ContainsKey($padre)) { $carpetas[$padre] = @('','','','') }
            for ($p = 0; $p -lt $permisos.Count; $p++) {
                if ([string]::IsNullOrWhiteSpace($permisos[$p])) { continue }
                if ($carpetas[$padre][$p] -ne 'RW') { $carpetas[$padre][$p] = 'RO' }
            }
        }
    }
    # Ordenar las carpetas por nombre y agregar cabeceras
    $carpetas = foreach ($carpeta in ($carpetas.Keys | Sort-Object)) {
        $contadorGrp = 0
        [System.Collections.ArrayList]$permisos = @()
        foreach ($p in $carpetas[$carpeta]) {
            $permisos.Add(@($cabecerasPermisos[$contadorGrp] , $p))
            $contadorGrp++
        }
        $permisos = $permisos | Sort-Object -Property @{Expression={ $_[0] }}
        [PSCustomObject]@{
            Carpeta  = $carpeta
            Permisos = $permisos
        }
    }
    return $carpetas
}