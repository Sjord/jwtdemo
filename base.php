<html>
<body>
<xmp>
<?php

require_once __DIR__ . '/common.php';

if (!isset($jwtImpl)) {
    die();
}

$token = getToken();

if ($token) {
    try {
        printValidJwt($jwtImpl->decodeJwt($token));
    } catch (Exception $e) {
        printInvalidJwt($e);
    }
} else {
    $jwt = $jwtImpl->encodeJwt(createTokenObject());
    echo "JWT: $jwt\n";
}
?>
</xmp>

<form method="POST">
    <textarea name="jwt" rows="5" cols="70"></textarea>
    <br>
    <input type="submit" value="Send JWT">
</form>

<form method="GET">
    <input type="submit" value="Get new JWTs">
</form>
