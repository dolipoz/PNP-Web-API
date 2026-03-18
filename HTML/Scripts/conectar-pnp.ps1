param(
    [string]$url,
    [string]$idcliente,
    [string]$certificado,
    [string]$tenant
)

Write-Output $url
Write-Output $idcliente
Write-Output $certificado
Write-Output $tenant
#Connect-PnPOnline -Url $url -ClientId $idcliente -CertificatePath $certificado -Tenant $tenant
#Get-PnPWeb