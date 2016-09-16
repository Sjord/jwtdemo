<?php

require __DIR__ . '/common.php';

function urlsafeB64Encode($input)
{
    return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
}

function urlsafeB64Decode($input)
{
    $remainder = strlen($input) % 4;
    if ($remainder) {
        $padlen = 4 - $remainder;
        $input .= str_repeat('=', $padlen);
    }
    return base64_decode(strtr($input, '-_', '+/'));
}


function verifySignature($signature, $message, $algo, $key) {
    if ($algo == 'HS256') {
        return $signature == hash_hmac('SHA256', $message, $key, true);
    } else if ($algo == 'RS256') {
        return openssl_verify($message, $signature, $key, 'SHA256');
    } else if ($algo == 'none') {
        return $signature == '';
    } else {
        throw new Exception("Unsupported algorithm $algo");
    }
}

function decodeJwt($token) {
    list($header, $content, $signature) = explode('.', $token);
    $headerObj = json_decode(urlsafeB64Decode($header));
    $contentObj = json_decode(urlsafeB64Decode($content));

    $key = file_get_contents('public.pem');
    if (!verifySignature(urlsafeB64Decode($signature), "$header.$content", $headerObj->alg, $key)) {
        throw new Exception("Invalid signature");
    }

    return $contentObj;
}

function createSignature($message, $algo, $keys) {
    if ($algo == 'HS256') {
        return hash_hmac('SHA256', $message, $keys, true);
    } else if ($algo == 'RS256') {
        $signature = '';
        openssl_sign($message, $signature, $keys[0], 'SHA256');
        return $signature;
    } else if ($algo == 'none') {
        return '';
    } else {
        throw new Exception("Unsupported algorithm $algo");
    }
}

function encodeJwt($tokenObj, $algorithmName, $keys) {
    $headerObj = ["alg" => $algorithmName, "typ" => "JWT"];
    $header = urlsafeB64Encode(json_encode($headerObj));
    $content = urlsafeB64Encode(json_encode($tokenObj));
    $signature = urlsafeB64Encode(createSignature("$header.$content", $algorithmName, $keys));
    return "$header.$content.$signature";
}

include('base.php');
