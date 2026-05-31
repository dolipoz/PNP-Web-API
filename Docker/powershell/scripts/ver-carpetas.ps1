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

# Para recorrer todos los directorios dentro del Sharepoint usamos Get-PnPListItems
# La columna FileLeafRef da el nombre del directorio, el deFSObjType da si es fichero o directorio, HasUniqueRoleAssignments dice si tiene permisos propios o heredados
$directorios = Get-PnPListItem -List $Raiz -PageSize 5000 -Fields "FileLeafRef","FSObjType","HasUniqueRoleAssignments" | Sort-Object { $_.FieldValues.FileLeafRef }

write-output "+++++++++++++++++++++++++++++++"
# Recorremos los directorios buscados con Get-PnPListItems
foreach ($dir in $directorios) {
      # Si FSObjType es 1 significa que es un directorio, que es lo que buscamos
      if ($dir["FSObjType"] -ne 1) {
         continue
      }
   write-output $dir["Id"]
   write-output $dir["FileLeafRef"]
}
write-output "+++++++++++++++++++++++++++++++"

$item = Get-PnPListItem -List $Raiz -Id 1
Get-PnPProperty -ClientObject $item -Property RoleAssignments
foreach ($roleAssignment in $item.RoleAssignments) {
    $grupo = $roleAssignment.Member.Title
    $permisos = $roleAssignment.RoleDefinitionBindings | Select-Object -ExpandProperty Name
    [PSCustomObject]@{
        Grupo     = $grupo
        Permisos  = ($permisos -join ", ")
    }
}


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
