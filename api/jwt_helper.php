<?php
/**
 * jwt_helper.php
 * 
 * Funções para gerar e validar tokens JWT (JSON Web Token).
 * 
 * JWT é composto por três partes:
 * 1. Header   → informações sobre o algoritmo e tipo do token.
 * 2. Payload  → dados que queremos transmitir (ex: id, email, permissões).
 * 3. Signature→ garantia de integridade (HMAC SHA256 com chave secreta).
 * 
 * Formato final: header.payload.signature
 */

class JWT
{
    /**
     * Decodifica e valida um token JWT.
     * 
     * @param string $jwt          O token JWT recebido (header.payload.signature)
     * @param string $key          Chave secreta usada na assinatura
     * @param array  $allowed_algs Lista de algoritmos aceitos (padrão: HS256)
     * 
     * @return object $payload     Retorna os dados do token se for válido
     * @throws Exception           Se o token for inválido ou a assinatura não bater
     */
    public static function decode($jwt, $key, array $allowed_algs = ['HS256'])
    {
        // Divide o token em 3 partes: header, payload e signature
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            throw new Exception('Wrong number of segments'); // Token mal formatado
        }

        // Desestrutura as três partes do token
        list($headb64, $bodyb64, $cryptob64) = $tks;

        // Decodifica cada parte de Base64URL para JSON
        $header = json_decode(self::urlsafeB64Decode($headb64));
        $payload = json_decode(self::urlsafeB64Decode($bodyb64));
        $sig = self::urlsafeB64Decode($cryptob64);

        // Verifica se o algoritmo foi informado
        if (empty($header->alg)) {
            throw new Exception('Empty algorithm');
        }

        // Verifica se o algoritmo é permitido
        if (!in_array($header->alg, $allowed_algs)) {
            throw new Exception('Algorithm not allowed');
        }

        // Recalcula a assinatura com base no header e payload decodificados
        $sig_check = self::sign("$headb64.$bodyb64", $key, $header->alg);

        // Compara a assinatura recalculada com a recebida
        if (!hash_equals($sig_check, $sig)) {
            throw new Exception('Signature verification failed');
        }

        // Retorna o conteúdo do payload (os dados do usuário)
        return $payload;
    }

    /**
     * Assina uma mensagem usando o algoritmo informado.
     * 
     * @param string $msg Mensagem a ser assinada (header.payload)
     * @param string $key Chave secreta
     * @param string $alg Algoritmo de assinatura (padrão: HS256)
     * 
     * @return string     Assinatura binária
     */
    private static function sign($msg, $key, $alg = 'HS256')
    {
        switch ($alg) {
            case 'HS256':
                return hash_hmac('sha256', $msg, $key, true); // Assina usando HMAC SHA256
            default:
                throw new Exception('Algorithm not supported');
        }
    }

    /**
     * Decodifica uma string Base64URL.
     * 
     * Base64URL é uma variação do Base64 tradicional que:
     * - Usa "-" e "_" no lugar de "+" e "/"
     * - Remove "=" no final
     * 
     * @param string $input String codificada em Base64URL
     * @return string       String decodificada
     */
    private static function urlsafeB64Decode($input)
    {
        // Ajusta padding (=) caso necessário
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }

        // Substitui caracteres para formato Base64 padrão e decodifica
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
