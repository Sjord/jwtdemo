<html>
<body>
<xmp>
<?php
require __DIR__ . '/vendor/autoload.php';

$shared_key = "secret";
$private_key = file_get_contents('private.pem');
$public_key = file_get_contents('public.pem');

$auth_header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_REQUEST['jwt'];
if (stripos($auth_header, 'bearer ') === 0) {
    $auth_header = substr($auth_header, 7);
}

$algorithms = [
    'none' => new NoneAlgorithm(),
    'HS256' => new HS256Algorithm($shared_key),
    'RS256' => new RS256Algorithm($private_key, $public_key),
];

if ($auth_header) {
    $header = json_decode(base64_decode(substr($auth_header, 0, strpos($auth_header, '.'))));
    $key = $algorithms[$header->alg];
    try {
        $decoded = JWT::decode($auth_header, ['algorithm' => array_values($algorithms)]);
        echo "Valid JWT: ";
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

    foreach ($algorithms as $name => $algorithm) {
        $jwt = Jwt::encode($token, $algorithm);
        echo "$name: $jwt\n";
    }

    echo "Public key: \n$public_key\n";
}
?>
</xmp>

<form method="POST">
    <textarea name="jwt" rows="5" cols="70">
    </textarea>
    <br>
    <input type="submit" value="Send JWT">
</form>

<form method="GET">
    <input type="submit" value="Get new JWTs">
</form>
