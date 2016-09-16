<?php


function getToken() {
    $auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_REQUEST['jwt'] ?? null;
    if (stripos($auth_header, 'bearer ') === 0) {
        $auth_header = substr($auth_header, 7);
    }
    return $auth_header;
}

function getAlgorithmKeys() {
    $shared_key = "secret";
    $private_key = file_get_contents('private.pem');
    $public_key = file_get_contents('public.pem');

    return [
        'none' => '',
        'HS256' => $shared_key,
        'RS256' => [$private_key, $public_key],
    ];
}

function getAlgorithm($jwt) {
    $header = json_decode(base64_decode(substr($auth_header, 0, strpos($auth_header, '.'))));
    return $header->alg;
}

function createTokenObject() {
    return array(
        # Issuer
        "iss" => "http://demo.sjoerdlangkemper.nl/",

        # Issued at
        "iat" => time(),

        # Expire
        "exp" => time() + 120,

        "data" => [
            "hello" => "world"
        ]
    );
}

function printValidJwt($decoded) {
    echo "Valid JWT: ";
    print_r($decoded);
}

function printInvalidJwt($e) {
    echo "Invalid JWT: $e\n";
}

