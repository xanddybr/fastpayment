<?php

class JWT
{
    public static function decode($jwt, $key, array $allowed_algs = ['HS256'])
    {
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            throw new Exception('Wrong number of segments');
        }

        list($headb64, $bodyb64, $cryptob64) = $tks;
        $header = json_decode(self::urlsafeB64Decode($headb64));
        $payload = json_decode(self::urlsafeB64Decode($bodyb64));
        $sig = self::urlsafeB64Decode($cryptob64);

        if (empty($header->alg)) {
            throw new Exception('Empty algorithm');
        }

        if (!in_array($header->alg, $allowed_algs)) {
            throw new Exception('Algorithm not allowed');
        }

        $sig_check = self::sign("$headb64.$bodyb64", $key, $header->alg);
        if (!hash_equals($sig_check, $sig)) {
            throw new Exception('Signature verification failed');
        }

        return $payload;
    }

    private static function sign($msg, $key, $alg = 'HS256')
    {
        switch ($alg) {
            case 'HS256':
                return hash_hmac('sha256', $msg, $key, true);
            default:
                throw new Exception('Algorithm not supported');
        }
    }

    private static function urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
