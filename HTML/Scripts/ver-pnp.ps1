param(
    [string]$url,
    [string]$idcliente,
    [string]$certificado,
    [string]$tenant
)

#Write-Output $url
#Write-Output $idcliente
#Write-Output $certificado
#Write-Output $tenant
$conexion = Connect-PnPOnline -Url $url -ClientId $idcliente -CertificatePath $certificado -Tenant $tenant -ReturnConnection
Get-PnPListItem -List "Documentos Compartidos" -Connection $conexion