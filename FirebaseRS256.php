<?php
require __DIR__ . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

class FirebaseRS256 {
    function __construct() {
        $this->private_key = file_get_contents('private.pem');
        $this->public_key = file_get_contents('public.pem');
    }

    function encodeJwt($tokenObj) {
        return JWT::encode($tokenObj, $this->private_key, 'RS256');
    }

    function decodeJwt($token) {
        // Explicitly configured to be vulnerable:
        // we expect a RS256 signature, but also accept a HS256 signature.
        return JWT::decode($token, $this->public_key, ['RS256', 'HS256']);
    }
}
