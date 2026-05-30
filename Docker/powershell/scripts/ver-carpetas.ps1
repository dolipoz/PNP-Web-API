# Como parametros de entrada tenemos el tenant
# param (
#    [string]$tenant,
#    [string]$id_cliente,
#    [string]$cert
# )
# Importamos las funciones para realizar actualizaciones en la base de datos
#. \scripts\importaciones.ps1
#Connect-PnPOnline -Url $tenant.sharepoint.com -ClientId $id_cliente -Tenant $tenant.onmicrosoft.com -CertificatePath $cert

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
