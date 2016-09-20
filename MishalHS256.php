<?php
require __DIR__ . '/vendor/autoload.php';

use Jwt\Jwt;
use Jwt\Algorithm\NoneAlgorithm;
use Jwt\Algorithm\HS256Algorithm;

class MishalHS256 {
    function __construct() {
        $this->algorithms = [
            'none' => new NoneAlgorithm(),
            'HS256' => new HS256Algorithm('secret'),
        ];
    }

    function encodeJwt($tokenObj) {
        return Jwt::encode($tokenObj, $this->algorithms['HS256']);
    }

    function decodeJwt($token) {
        return JWT::decode($token, ['algorithm' => array_values($this->algorithms)]);
    }
}
