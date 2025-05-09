<?php
require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/env.php");

if (!isset($_POST['password']) || $_POST['password'] != $testPassword) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup</title>
</head>
<body>
    <form method="POST" action="">
        <label>Password:</label>
        <input type="password" name="password" />
        <input type="submit" name="Submit" value="Submti" />
    </form>
</body>
</html>
<?php
die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
    <style>
        body {
            background-color: #212529;
        }
    </style>
</head>
<body>
    <?php
require_once("$basePath/lib/result.php");

$result = new Result("Cryptography");

require_once("$basePath/lib/cryptography.php");

if (base64UrlSafeEncode("Abcaa") == "QWJjYWE") {
    $result->works("base64UrlSafeEncode");
} else {
    $result->fails("base64UrlSafeEncode");
}

if (base64UrlSafeDecode("QWJjYWE") == "Abcaa") {
    $result->works("base64UrlSafeDecode");
} else {
    $result->fails("base64UrlSafeDecode");
}

$result->printResult();



$result = new Result("Security");

require_once("$basePath/lib/security.php");

$encryptedPassword = passwordEncryption("demo");
if (strlen($encryptedPassword) == 97 && substr($encryptedPassword, 0, 31) == '$argon2id$v=19$m=65536,t=4,p=1$') {
    $result->works("passwordEncryption");
} else {
    $result->fails("passwordEncryption");
}

if (passwordVerifiy("demo", $encryptedPassword)) {
    $result->works("passwordVerifiy");
} else {
    $result->fails("passwordVerifiy");
}

$token = JWTEncrypt($JWTPassword, 1, 3600);
if (count(explode('.', $token))) {
    $result->works("JWTEncrypt");
} else {
    $result->fails("JWTEncrypt");
}

// TODO: Fail causes (Expired + Check Sign)
if (JWTVerifiy($JWTPassword, $token)[0]) {
    $result->works("JWTVerifiy");
} else {
    $result->fails("JWTVerifiy");
}

$result->printResult();

    ?>
</body>
</html>