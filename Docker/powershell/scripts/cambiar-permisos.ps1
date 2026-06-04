# Como parametros de entrada tenemos el tenant
param (
   [string]$tenant,
   [string]$sitio,
   [string]$id_cliente,
   [string]$cert,
   $csvJson,
   [string]$nombre_contenedor,
   [int32]$index
)
# Importamos las funciones para realizar actualizaciones en la base de datos
. \scripts\importaciones.ps1
# Si el Sitio está configurado en otro idioma diferente al Español, cambiar según el lenguaje la raíz, por ejemplo en inglés sería "Documents"
$raiz = "Documentos compartidos"

$RutaCert = "/certs/$cert.pfx"
$password = ConvertTo-SecureString $env:PFX_PASS -Force -AsPlainText
write-output "Mostrar Carpetas"
# Llamamos a la función de ObtenerCarpetas alojada en Importaciones
$csv = $csvJson | ConvertFrom-Json
$carpetas = ObtenerCarpetas $csv


# Creamos la conexión con la api de PNP indicando el URL de sharepoint, el id cliente de la api, el tenant, el certificado asociado a la api y la contraseña por defecto almacenada en el entorno del contenedor
Connect-PnPOnline -Url "$tenant.sharepoint.com/sites/$sitio" -ClientId $id_cliente -Tenant "$tenant.onmicrosoft.com" -CertificatePath $RutaCert -CertificatePassword $password

ejecutarquery("update tareas set progreso = 33 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
# # Obtener todas las carpetas
$directorios = Get-PnPListItem -List $Raiz -PageSize 500 -Fields "FileLeafRef","FSObjType","HasUniqueRoleAssignments" | Sort-Object { $_.FieldValues.FileLeafRef }

foreach ($c in $carpetas) {
   $cn = $c.Carpeta
   $carpeta = $directorios | Where-Object { $_.FieldValues.FileLeafRef -match "^$cn\s" }
   Write-Output $directorio.FieldValues.FileLeafRef
   foreach ($g in $c.Permisos) {
      $grupo = $g[0]
      $permiso = $traducirPermisos[$g[1]]
      write-output "El grupo $grupo puede $permiso"
   }
}









# foreach ($directorio in $directorios) {
#     # Cargar propiedad HasUniqueRoleAssignments
#     Get-PnPProperty -ClientObject $directorio -Property HasUniqueRoleAssignments
#     if (-not $directorio.HasUniqueRoleAssignments) {
#         Write-Output "Rompiendo herencia en: $($directorio['FileLeafRef'])"
#         # Romper herencia
#         $directorio.BreakRoleInheritance($true, $true)
#         $directorio.Context.ExecuteQuery()

#         # Obtenemos el grupo 
#         $grupo = Get-PnPUser | Where-Object { $_.Title -eq "G01" }
#         Set-PnPListItemPermission `
#             -List $raiz `
#             -Identity $directorio.Id `
#             -User $grupo.LoginName `
#             -AddRole "Colaborar"
#         Write-Output "Permisos aplicados."
#         break
#     }
# }
Disconnect-PnPOnline
ejecutarquery("update tareas set estado = 'completada', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")