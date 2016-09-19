<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/common.php';

use \Firebase\JWT\JWT;

JWT::$leeway = 10; // $leeway in seconds

function encodeJwt($tokenObj, $algorithmName, $keys) {
    if ($algorithmName == 'none') {
        return "not supported";
    }

    $key = $keys;
    if (is_array($keys)) {
        $key = $keys[0];
    }
    return JWT::encode($tokenObj, $key, $algorithmName);
}

function decodeJwt($token) {
    $keys = getAlgorithmKeys();
    $key = $keys['RS256'][1];

    return JWT::decode($token, $key, array_keys($keys));
}

include('base.php');
