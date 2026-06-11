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

try {
   ejecutarquery("update tareas set progreso = 1 where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
   # Si el Sitio está configurado en otro idioma diferente al Español, cambiar según el lenguaje la raíz, por ejemplo en inglés sería "Documents"
   $raiz = "Documentos compartidos"

   $RutaCert = "/certificados/$cert.pfx"
   $password = ConvertTo-SecureString $env:PWSH_PASS -Force -AsPlainText
   write-output "Mostrar Carpetas"
   # Llamamos a la función de ObtenerCarpetas alojada en Importaciones
   $csv = $csvJson | ConvertFrom-Json
   $carpetas = ObtenerCarpetas $csv

   # Creamos la conexión con la api de PNP indicando el URL de sharepoint, el id cliente de la api, el tenant, el certificado asociado a la api y la contraseña por defecto almacenada en el entorno del contenedor
   Connect-PnPOnline -Url "$tenant.sharepoint.com/sites/$sitio" -ClientId $id_cliente -Tenant "$tenant.onmicrosoft.com" -CertificatePath $RutaCert -CertificatePassword $password
   # # Obtener todas las carpetas
   $directorios = Get-PnPListItem -List $Raiz -PageSize 500 -Fields "FileLeafRef","FSObjType","HasUniqueRoleAssignments" | Sort-Object { $_.FieldValues.FileLeafRef }
   $salto = $carpetas | Measure-Object
   $salto = 99 / $salto.Count
   $progreso = 1
   foreach ($c in $carpetas) {
      $cn = $c.Carpeta -replace("\.","\.")
      $carpeta = $directorios | Where-Object { $_.FieldValues.FileLeafRef -match "^$cn\.\s" }
      if ($carpeta.FieldValues.FileLeafRef) {
         # Cargar propiedad HasUniqueRoleAssignments para ver si tiene permisos únicos, si no los tiene romper herencia
         Get-PnPProperty -ClientObject $carpeta -Property HasUniqueRoleAssignments
         if (-not $carpeta.HasUniqueRoleAssignments) {
            #Write-Output "Rompiendo herencia en: $($carpeta.FieldValues.FileLeafRef)"
            # Romper herencia
            $carpeta.BreakRoleInheritance($false, $true)
            $carpeta.Context.ExecuteQuery()
            # Recorremos los permisos asociados en el CSV
            foreach ($g in $c.Permisos) {
               # Si está vacío no se añade nada
               if ($g[1] -ne "") {
                  # Agregamos el grupo con el permiso de RO o RW
                  Set-PnPListItemPermission `
                     -List $raiz `
                     -Identity $carpeta.Id `
                     -User $g[0] `
                     -AddRole $traducirPermisos[$g[1]] | Out-Null
                  #Write-Output $g[0],$g[1]
               }
            }
         }
      }
      $progreso += $salto
      ejecutarquery("update tareas set progreso = $([math]::Round($progreso)) where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
   }
   ejecutarquery("update tareas set estado = 'completada', progreso = 100, f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
} catch {
   $_ | out-file -filepath "/certificados/errores.log" -append
   $errorMsg = "$($_.InvocationInfo.ScriptName) -- $($_.InvocationInfo.Line) -- $($_.ErrorDetails.Message)"
   $b64 = [Convert]::ToBase64String( [Text.Encoding]::UTF8.GetBytes($errorMsg) )
   write-output $_
   ejecutarquery("update tareas set estado = 'fallida', error = '$b64', f_finalizacion = current_timestamp, bloqueo = null where estado = 'ejecutando' and nombre_contenedor = '$nombre_contenedor' and id_tarea = $index;")
}
Disconnect-PnPOnline