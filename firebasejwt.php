<?php
require __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;

JWT::$leeway = 10; // $leeway in seconds

$shared_key = "secret";
$private_key = file_get_contents('private.pem');
$public_key = file_get_contents('public.pem');

$auth_header = $_SERVER['HTTP_AUTHORIZATION'];
if (stripos($auth_header, 'bearer ') == 0) {
    $auth_header = substr($auth_header, 7);
}

JWT::$supported_algs['none'] = ['hash_hmac', 'none'];
$algorithms = [
    'none' => 'none',
    'HS256' => $shared_key,
    'RS256' => $public_key,
];

if ($auth_header) {
    $header = json_decode(base64_decode(substr($auth_header, 0, strpos($auth_header, '.'))));
    $key = $algorithms[$header->alg];
    try {
        $decoded = JWT::decode($auth_header, $key, array_keys($algorithms));
        echo "Valid $algorithm JWT: ";
        print_r($decoded);
    } catch (Exception $e) {
        echo "Invalid JWT: $e\n";
    }
} else {
    $token = array(
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

    echo '<xmp>';

    $jwt = JWT::encode($token, $shared_key, 'HS256');
    echo "HS256: $jwt\n";

    $jwt = JWT::encode($token, $private_key, 'RS256');
    echo "RS256: $jwt\n";

    echo "Public key: \n$public_key\n";
}
