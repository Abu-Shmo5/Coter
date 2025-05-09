<?php

function passwordEncryption($password) {
    return password_hash($password, PASSWORD_ARGON2ID);
}

function passwordVerifiy($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

function JWTEncrypt($password, $content, $expFromNow=3600) {
    $header = base64UrlSafeEncode(["typ" => "JWT", "alg" => "HS256"]);
    $exp = time() + $expFromNow;
    $body = base64UrlSafeEncode(["content" => $content, "exp" => $exp]);
    $sign = hash_hmac("sha256", "$header.$body", $password);
    return "$header.$body.$sign";
}

function JWTVerifiy($password, $jwt) {
    $jwtParts = explode(".", $jwt);
    $header = $jwtParts[0];
    $body = $jwtParts[1];
    $sign = $jwtParts[2];
    return [$sign == hash_hmac("sha256", "$header.$body", $password), base64UrlSafeDecode($body)];
}