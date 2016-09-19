<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/common.php';

use \Firebase\JWT\JWT;

$public_key = file_get_contents('public.pem');
echo JWT::encode(createTokenObject(), $public_key, 'HS256');
