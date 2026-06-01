# Como parametros de entrada tenemos el tenant
param (
   [string]$tenant,
   [string]$sitio,
   [string]$id_cliente,
   [string]$cert,
   [string]$nombre_contenedor,
   [int32]$index
)
# Importamos las funciones para realizar actualizaciones en la base de datos
. \scripts\importaciones.ps1
# Si el Sitio está configurado en otro idioma diferente al Español, cambiar según el lenguaje la raíz, por ejemplo en inglés sería "Documents"
$raiz = "Documentos compartidos"

$RutaCert = "/certs/$cert.pfx"
$password = ConvertTo-SecureString $env:PFX_PASS -Force -AsPlainText
# Creamos la conexión con la api de PNP indicando el URL de sharepoint, el id cliente de la api, el tenant, el certificado asociado a la api y la contraseña por defecto almacenada en el entorno del contenedor
Connect-PnPOnline -Url "$tenant.sharepoint.com/sites/$sitio" -ClientId $id_cliente -Tenant "$tenant.onmicrosoft.com" -CertificatePath $RutaCert -CertificatePassword $password
ejecutarquery("update tareas set progreso = 33 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
# Obtener todas las carpetas
$directorios = Get-PnPListItem -List $Raiz -PageSize 500 -Fields "FileLeafRef","FSObjType","HasUniqueRoleAssignments" | Sort-Object { $_.FieldValues.FileLeafRef }
foreach ($directorio in $directorios) {
    if ($directorio['FSObjType'] -ne 1) {
        # Si FSObjType no es 1 significa que es un directorio, que es lo que buscamos
        continue
    }
    # Cargar propiedad HasUniqueRoleAssignments
    Get-PnPProperty -ClientObject $directorio -Property HasUniqueRoleAssignments | Out-Null
    if ($directorio.HasUniqueRoleAssignments) {
        Write-Output "Devolvemos la herencia en: $($directorio['FileLeafRef'])"
        # Romper herencia
        Set-PnPListItemPermission -List $raiz -Identity $directorio.Id -InheritPermissions
        write-output "Herencia devuelta"
    }
}
Disconnect-PnPOnline
ejecutarquery("update tareas set estado = 'completada', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")