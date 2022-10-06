<?php

namespace App\Services;

use DateTimeImmutable;

class JsonWebTokenServices
{

    // on génére le token 
    public function generate(array $header, array $payload, string $secret, int $validity = 1200): string
    {
        if ($validity <= 0) {
            return "";
        }
        $nowDate = new DateTimeImmutable();
        $expired = $nowDate->getTimestamp() + $validity;

        $payload['iat'] = $nowDate->getTimestamp();
        $payload['exp'] = $expired;
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));

        //on "nettoie" les valeurs encodés (retrait des +, / et = );
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);
        //on génére la signature 
        $secret = base64_encode($secret);
        //cela va me permettre de hasher les base64header et base64Payload avec du sha1 , cela va générer une clé 

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);

        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        $jsonWebToken = $base64Header . '.' . $base64Payload . '.' . $base64Signature;



        return $jsonWebToken;
    }
}
