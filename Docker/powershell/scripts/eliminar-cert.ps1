param (
   [string]$nombre,
   [string]$nombre_contenedor,
   [int32]$index
)
# Importamos las variables y funciones
. \scripts\importaciones.ps1

$pfx = "/certs/$nombre.pfx"
# Eliminar el Certificado
Remove-Item $pfx -Recurse -Force
ejecutarquery("update tareas set progreso = 33 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
# Creamos los certificados en la base de datos
ejecutarquery("delete from certificados where nombre = '$nombre';")
ejecutarquery("update tareas set progreso = 66 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
ejecutarquery("update tareas set estado = 'completada', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")