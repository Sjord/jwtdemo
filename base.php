<html>
<body>
<xmp>
<?php

$token = getToken();
$algorithms = getAlgorithmKeys();

if ($token) {
    try {
        printValidJwt(decodeJwt($token));
    } catch (Exception $e) {
        printInvalidJwt($e);
    }
} else {
    foreach ($algorithms as $name => $keys) {
        $jwt = encodeJwt(createTokenObject(), $name, $keys);
        echo "$name: $jwt\n";
    }
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
