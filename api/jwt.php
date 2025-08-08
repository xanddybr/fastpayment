<?php

// Função para gerar um JWT (JSON Web Token)
function generateJWT($payload) {
    
    // 1. Cabeçalho do JWT com o algoritmo de assinatura (HS256) e tipo (JWT)
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];

    // 2. Chave secreta usada para gerar a assinatura do token
    $key = 'chave_secreta';

    // 3. Codifica o cabeçalho em JSON e depois em Base64URL (formato específico do JWT)
    $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');

    // 4. Codifica o payload (dados do usuário ou da sessão) em JSON e depois em Base64URL
    $base64UrlPayload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

    // 5. Gera a assinatura usando HMAC com SHA256:
    //    - Junta cabeçalho e payload com ponto "."
    //    - Usa a chave secreta
    //    - O resultado é a "assinatura" que garante a integridade do token
    $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", $key, true);

    // 6. Codifica a assinatura para Base64URL
    $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

    // 7. Junta tudo no formato padrão JWT: header.payload.signature
    return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
}
