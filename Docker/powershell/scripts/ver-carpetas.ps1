# Como parametros de entrada tenemos el tenant
param (
   [string]$tenant,
   [string]$id_cliente,
   [string]$cert,
   [string]$nombre_contenedor,
   [int32]$index
)
# Importamos las funciones para realizar actualizaciones en la base de datos
. \scripts\importaciones.ps1

$raiz = "Documentos compartidos"

$RutaCert = "/certs/$cert.pfx"
$password = ConvertTo-SecureString $env:PFX_PASS -Force -AsPlainText
# Creamos la conexión con la api de PNP indicando el URL de sharepoint, el id cliente de la api, el tenant, el certificado asociado a la api y la contraseña por defecto almacenada en el entorno del contenedor
Connect-PnPOnline -Url "$tenant.sharepoint.com" -ClientId $id_cliente -Tenant "$tenant.onmicrosoft.com" -CertificatePath $RutaCert -CertificatePassword $password
ejecutarquery("update trabajos set progreso = 66 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")


#$grupo = Get-PnPGroup -Identity "$tenant\G01"
#$grupo = Get-PnPUser | Where-Object { $_.Title -eq "G01" }
#write-output $grupo


#$login = "c:0t.c|tenant|689e87ae-1f65-4bbd-8985-986d840d804a"

Set-PnPListItemPermission `
   -List $raiz `
   -Identity 1 `
   -Group "G01" `
   -AddRole "Colaborar"


# Para recorrer todos los directorios dentro del Sharepoint usamos Get-PnPListItems
# La columna FileLeafRef da el nombre del directorio, el deFSObjType da si es fichero o directorio, HasUniqueRoleAssignments dice si tiene permisos propios o heredados
# $directorios = Get-PnPListItem -List $Raiz -PageSize 5000 -Fields "FileLeafRef","FSObjType","HasUniqueRoleAssignments" | Sort-Object { $_.FieldValues.FileLeafRef }

# write-output "+++++++++++++++++++++++++++++++"
# # Recorremos los directorios buscados con Get-PnPListItems
# foreach ($dir in $directorios) {
#    $HasUniqueRoleAssignments = Get-PnPProperty -ClientObject $dir -Property HasUniqueRoleAssignments
#    #Write-Output $dirPNP.PSObject.Properties.Name
#    #$HasUniqueRoleAssignments = Get-PnPProperty -ClientObject $dir -Property HasUniqueRoleAssignments
#    Write-Output $dir['FileLeafRef']
#    #Write-Output $dir['FSObjType']
   
#    if ($dir['FSObjType'] -ne 1) {
#       # Si FSObjType no es 1 significa que es un directorio, que es lo que buscamos
#       continue
#    }
   
#    if ($HasUniqueRoleAssignments -eq $true) {
#       #Write-Output $dir.PSObject.Properties.Name
#       $roleAssignments = Get-PnPProperty -ClientObject $dir -Property RoleAssignments
#       foreach ($roleAssignment in $roleAssignments) {
#          #$grupo = $roleAssignment.Member.Title
#          $grupo = Get-PnPProperty -ClientObject $roleAssignment -Property Member
#          $permisos = Get-PnPProperty -ClientObject $roleAssignment -Property RoleDefinitionBindings
#          write-output $grupo.PSObject.Properties.Name
#          #write-output $permisos.Name
#       }
#    }
#    break
# }
# write-output "+++++++++++++++++++++++++++++++"


#ejecutarquery("update trabajos set salida = '{".'"resultado"'.":$directorios}', progreso = 99 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")
# Para cerrar la sesion de PNP
Disconnect-PnPOnline
# $rcsv = "D:\\EjemploCSV.csv"
# $csv = Import-Csv -Path $rcsv -Delimiter ";"

# $cabecerasNiveles = $csv[0].psobject.properties.name | where-object { $_ -match "^Nivel" }
# $cabecerasPermisos = $csv[0].psobject.properties.name | where-object { $_ -notmatch "^Nivel" }
# $csv = $csv | where-object { $_.psobject.properties.Value -ne  '' }
# $csvLimite = $csv | Measure-Object
# $csvLimite = $csvLimite.Count-1
# $nivel1 = $nivel2 = $nivel3 = $nivel4 = 0

# $contador = 0
# foreach ($fila in $csv) {
#     if ($contador -eq 0) {
#         write-output "$contador principio"
#     } elseif ($contador -eq $csvLimite) {
#         write-output $cabecerasNiveles | foreach-object {$fila.$_}
#     } else {
#         write-output "$contador normal"
#     }
#     $contador++
# }
ejecutarquery("update trabajos set estado = 'Completado', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_trabajo = $index;")