<?php
    // Obtener token de acceso para PNP.powershell
    function generarToken($tenant, $tenantId, $clientId, $clientSecret) {
        // URL de petición para la obtención del Token de microsoft
        $url = "https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token";
        // Array de datos (OAuth 2.0) para la petición del token con los datos del cliente
        $data = [
            "grant_type" => "client_credentials",
            "client_id" => $clientId,
            "client_secret" => $clientSecret,
            "scope" => "https://{$tenant}.sharepoint.com/.default"
        ];

        $ci = curl_init($url);
        curl_setopt($ci, CURLOPT_POST, true);
        curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        // Ejecutamos el CURL para que nos devuelva el token en formato json y convertirlo a array php
        $response = curl_exec($ci);
        $json = json_decode($response, true);

        if(isset($json["access_token"])){
            return $json["access_token"];
        } else {
            $_SESSION['error'] = true;
            $_SESSION['info'] = $response;
            return null;
        }
    }
?>