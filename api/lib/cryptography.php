<?php

function base64UrlSafeEncode($content) {
    if (gettype($content) == "object" || gettype($content) == "array") {
        $content = json_encode($content);
    }
    return trim(strtr(base64_encode($content), '+/', '-_'), '=');
}

function base64UrlSafeDecode($content) {
    return base64_decode(strtr($content, '-_', '+/'));
}

