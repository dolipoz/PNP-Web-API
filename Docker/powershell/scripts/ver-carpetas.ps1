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
ejecutarquery("update tareas set progreso = 66 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")

# Para recorrer todos los directorios dentro del Sharepoint usamos Get-PnPListItems
# La columna FileLeafRef da el nombre del directorio, el deFSObjType da si es fichero o directorio, HasUniqueRoleAssignments dice si tiene permisos propios o heredados
$directorios = Get-PnPListItem -List $Raiz -PageSize 500 -Fields "FileLeafRef","FSObjType","HasUniqueRoleAssignments" | Sort-Object { $_.FieldValues.FileLeafRef }

$json = @{}

write-output "+++++++++++++++++++++++++++++++"
# Recorremos los directorios buscados con Get-PnPListItems
foreach ($dir in $directorios) {
   $HasUniqueRoleAssignments = Get-PnPProperty -ClientObject $dir -Property HasUniqueRoleAssignments
   #Write-Output $dirPNP.PSObject.Properties.Name
   #$HasUniqueRoleAssignments = Get-PnPProperty -ClientObject $dir -Property HasUniqueRoleAssignments
   Write-Output $dir['FileLeafRef']
   #Write-Output $dir['FSObjType']
   
   if ($dir['FSObjType'] -ne 1) {
      # Si FSObjType no es 1 significa que es un directorio, que es lo que buscamos
      continue
   }
   
   if ($HasUniqueRoleAssignments -eq $true) {
      #Write-Output $dir.PSObject.Properties.Name
      $roleAssignments = Get-PnPProperty -ClientObject $dir -Property RoleAssignments
      foreach ($roleAssignment in $roleAssignments) {
         #$grupo = $roleAssignment.Member.Title
         $grupo = Get-PnPProperty -ClientObject $roleAssignment -Property Member
         $permisos = Get-PnPProperty -ClientObject $roleAssignment -Property RoleDefinitionBindings
         $json[$grupo.PSObject.Properties.Name] = $permisos.Name
         #write-output $permisos.Name
      }
   }
}
write-output "+++++++++++++++++++++++++++++++"

# Para cerrar la sesion de PNP
Disconnect-PnPOnline
ejecutarquery("update tareas set estado = 'completada', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")