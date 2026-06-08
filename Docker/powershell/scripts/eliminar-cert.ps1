param (
   [string]$nombre,
   [string]$nombre_contenedor,
   [int32]$index
)
# Importamos las variables y funciones
. \scripts\importaciones.ps1

try {
   ejecutarquery("update tareas set progreso = 1 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
   # Eliminamos el certificado con el nombre recibido de la tarea
   $pfx = "/certs/$nombre.pfx"
   Remove-Item $pfx -Recurse -Force
   # Eliminamos de la base de datos el registro del certificado que hemos eliminado físicamente
   ejecutarquery("delete from certificados where nombre = '$nombre';")
   ejecutarquery("update tareas set estado = 'completada', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
} catch {
    $_ | out-file -filepath "/certs/errores.log" -append
    $errorMsg = "$($_.InvocationInfo.ScriptName) -- $($_.InvocationInfo.Line) -- $($_.ErrorDetails.Message)"
    $b64 = [Convert]::ToBase64String( [Text.Encoding]::UTF8.GetBytes($errorMsg) )
    write-output $_
    ejecutarquery("update tareas set estado = 'fallida', error = '$b64', f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
}