<?php

namespace App\Service;

class JWTService
{
    /**
     * Génère un token JWT.
     * 
     * @param array $header   L'entête du token, généralement contenant le type et l'algorithme de signature.
     * @param array $payload  Les données ou revendications du token.
     * @param string $secret  La clé secrète utilisée pour signer le token.
     * @param int $validity   La durée de validité du token en secondes (défaut : 10800).
     * @return string         Le token JWT généré.
     */
    public function generate(
        array $header,
        array $payload,
        string $secret,
        int $validity = 10800
    ): string {
        // Gestion de la validité du token
        if ($validity > 0) {
            $now = new \DateTimeImmutable();
            $exp = $now->getTimestamp() + $validity;
            // Ajout des champs 'iat' (Issued At) et 'exp' (Expiration) dans le payload
            $payload['iat'] = $now->getTimestamp();
            $payload['exp'] = $exp;
        }
        // Encodage des entêtes et du payload en Base64
        $base64Header = base64_encode(json_encode($header));
        $base64Payload = base64_encode(json_encode($payload));
        // Remplacement de certains caractères pour le formatage du JWT
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);
        // Encodge du secret en Base64
        $secret = base64_encode($secret);
        // Création de la signature
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);
        // Encodage de la signature en Base64
        $base64Signature = base64_encode($signature);
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);
        // Assemblage du token JWT
        $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;
        return $jwt;
    }
    /**
     * Vérifie si le format du token est valide.
     *
     * @param string $token  Le token JWT à valider.
     * @return bool          Vrai si le format du token est valide, faux sinon.
     */
    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+\.[a-zA-Z0-9\-\_\=]+$/',
            $token
        ) === 1;
    }
    /**
     * Récupère l'entête du token JWT.
     *
     * @param string $token  Le token JWT.
     * @return array         L'entête du token sous forme de tableau.
     */
    public function getHeader(string $token)
    {
        $array = explode('.', $token);
        $header = json_decode(base64_decode($array[0]), true);
        return $header;
    }
    /**
     * Récupère le payload du token JWT.
     *
     * @param string $token  Le token JWT.
     * @return array         Le payload du token sous forme de tableau.
     */
    public function getPayload(string $token)
    {
        $array = explode('.', $token);
        $payload = json_decode(base64_decode($array[1]), true);
        return $payload;
    }
    /**
     * Vérifie si le token JWT est expiré.
     *
     * @param string $token  Le token JWT.
     * @return bool          Vrai si le token est expiré, faux sinon.
     */
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        $now = new \DateTimeImmutable();

        return $payload['exp'] < $now->getTimestamp();
    }
    /**
     * Vérifie l'authenticité du token JWT.
     *
     * @param string $token  Le token JWT.
     * @param string $secret La clé secrète utilisée lors de la génération du token.
     * @return bool          Vrai si le token est authentique, faux sinon.
     */
    public function check(string $token, string $secret)
    {
        $header = $this->getHeader($token);
        $payload = $this->getPayload($token);
        // Regénère le token avec les données extraites et le compare avec le token fourni
        $verifToken = $this->generate($header, $payload, $secret, 0);
        return $token === $verifToken;
    }
}
