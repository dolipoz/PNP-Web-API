param (
   [string]$nombre,
   [string]$pais,
   [string]$ciudad,
   [string]$localidad,
   [int32]$expira
)
$params = @{
    Type = 'Custom'
    Subject = "L=$localidad, ST=$ciudad, C=$pais"
    NotAfter = (Get-Date).AddYears($expira)
    HashAlgorithm = 'sha256'
}
# Creamos el certificado autofirmado con parametros por defecto
$cert = New-SelfSignedCertificate @params

# Securizamos la contraseña PFX para exportar el certificado a la ruta personalizada
$password = ConvertTo-SecureString $env:PFX_PASS -AsPlainText -Force
# Exportar el certificado a la ruta personalizada (con clave privada)
Export-PfxCertificate -Cert $cert -Password $password -FilePath "/certs/$nombre.pfx"
# Eliminamos el certificado de la ruta por defecto
Remove-Item -Path $cert.PSPath

# Sacamos el certificado sin la clave privada en formato texto para agregarlo a la base de datos
$cert64 = ([System.Convert]::ToBase64String($cert.RawData))
$certData = @"
-----BEGIN CERTIFICATE-----
$cert64
-----END CERTIFICATE-----
"@

write-output $certData
