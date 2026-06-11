param (
   [string]$nombre,
   [string]$pais,
   [string]$ciudad,
   [string]$localidad,
   [int32]$expira,
   [string]$nombre_contenedor,
   [int32]$index
)
# Importamos las variables y funciones
. \scripts\importaciones.ps1

try {
    $llave = "/certificados/$nombre.key"
    $cert = "/certificados/$nombre.cer"
    $pfx = "/certificados/$nombre.pfx"

    # Generar certificado y clave privada
    $subject = "/C=$pais/ST=$ciudad/L=$localidad/CN=$nombre"
    openssl req -x509 -newkey rsa:2048 -sha256 -days ($expira * 365) -nodes -keyout $llave -out $cert -subj $subject 2>$null
    if ($LASTEXITCODE -ne 0) {
        throw "Error generando certificado con OpenSSL"
    }
    ejecutarquery("update tareas set progreso = 33 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
    # Exportar pfx para sesiones PNP
    openssl pkcs12 -export -out $pfx -inkey $llave -in $cert -password "pass:$($env:PWSH_PASS)"
    if ($LASTEXITCODE -ne 0) {
        throw "Error exportando PFX"
    }
    ejecutarquery("update tareas set progreso = 66 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
    # Obtener el certificado público en Base64
    $cert64 = Get-Content $cert -raw

    # Limpiar temporales
    Remove-Item $llave -Recurse -Force
    Remove-Item $cert -Recurse -Force

    # Creamos los certificados en la base de datos
    ejecutarquery("insert into certificados (nombre, contenido, f_creado, expira) values ('$nombre', '$cert64', current_timestamp, date_add(current_timestamp,interval $expira year));")
    ejecutarquery("update tareas set estado = 'completada', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")

} catch {
    $_ | out-file -filepath "/certificados/errores.log" -append
    $errorMsg = "$($_.InvocationInfo.ScriptName) -- $($_.InvocationInfo.Line) -- $($_.ErrorDetails.Message)"
    $b64 = [Convert]::ToBase64String( [Text.Encoding]::UTF8.GetBytes($errorMsg) )
    write-output $_
    ejecutarquery("update tareas set estado = 'fallida', error = '$b64', f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
}
