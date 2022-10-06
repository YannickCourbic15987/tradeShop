<?php

namespace App\Services;

use DateTimeImmutable;

class JsonWebTokenServices
{

    // on génére le token 
    public function generate(array $header, array $payload, string $secret, int $validity = 10800): string
    {
        if ($validity > 0) {
            // return "";
            $nowDate = new DateTimeImmutable();
            $expired = $nowDate->getTimestamp() + $validity;

            $payload['iat'] = $nowDate->getTimestamp();
            $payload['exp'] = $expired;
        }

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

    //on vérifie que le token est valide (correctememnt formé)

    public function tokenIsValid(string $token): bool
    {

        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }

    // on récupère le Payload 
    public function getPayload(string $token): array
    {
        //on le sépare le token à chaque point , 
        $array = explode('.', $token);

        $payload = json_decode(base64_decode($array[1]), true);

        return $payload;
    }
    public function getHeader(string $token): array
    {
        //on le sépare le token à chaque point , 
        $array = explode('.', $token);
        //on décode le token 
        $header = json_decode(base64_decode($array[0]), true);

        return $header;
    }

    //on vérifie si le token a expiré 

    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $nowDate = new DateTimeImmutable();

        return $payload['exp'] < $nowDate->getTimestamp();
    }

    // on vérifie la signature du token 

    public function checkToken(string $token, string $secret)
    {
        //on récup header + payload
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);
        $verifToken = $this->generate($header, $payload, $secret, 0);
        return $token === $verifToken;
    }
}
