<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/common.php';

use Jwt\Jwt;
use Jwt\Algorithm\NoneAlgorithm;
use Jwt\Algorithm\HS256Algorithm;
use Jwt\Algorithm\RS256Algorithm;

function getAlgorithmObjects() {
    $keys = getAlgorithmKeys();

    $algorithms = [
        'none' => new NoneAlgorithm(),
        'HS256' => new HS256Algorithm($keys['HS256']),
        'RS256' => new RS256Algorithm($keys['RS256'][0], $keys['RS256'][1]),
    ];
    return $algorithms;
}

function decodeJwt($token) {
    $algorithms = getAlgorithmObjects();
    return JWT::decode($token, ['algorithm' => array_values($algorithms)]);
}

function encodeJwt($tokenObj, $algorithmName, $keys) {
    $algorithms = getAlgorithmObjects();
    return Jwt::encode($tokenObj, $algorithms[$algorithmName]);
}

include('base.php');
