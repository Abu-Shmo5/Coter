<?php

require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/env.php");
$output = ["Status" => "Failed", "Message" => ""];
if (isset($_POST['action']) && $_POST['action'] == 'register' && isset($_POST['username']) && isset($_POST['password'])) {
    require_once("$basePath/lib/validator.php");
    $username = $_POST['username'];
    if (!isValidUsername($username)) {
        $output['Message'] = "Invalid username";
        echo(json_encode($output));
        die();
    }

    $password = $_POST['password'];
    if (!isValidPassword($password)) {
        $output['Message'] = "Invalid password";
        echo(json_encode($output));
        die();
    }

    require_once("$basePath/lib/cryptography.php");
    require_once("$basePath/lib/security.php");
    $hashedPassword = passwordEncryption($password);

    require_once("$basePath/lib/database.php");
    $connection = databaseConnection($databaseHostname, $databaseUsername, $databasePassword, $databaseName);

    $statment = $connection->prepare("SELECT account_id FROM Accounts WHERE username = ?;");
    $statment->bind_param("s", $username);

    if (!$statment->execute()) {
        $output['Message'] = "Failed to check available usernames";
        echo(json_encode($output));
        die();
    }

    $result = $statment->get_result();
    if ($result->num_rows != 0) {
        $output['Message'] = "Inavailable username";
        echo(json_encode($output));
        die();
    }

    $statment->close();

    $statment = $connection->prepare("INSERT INTO Accounts (username, password) VALUES (?, ?);");
    $statment->bind_param("ss", $username, $hashedPassword);

    if (!$statment->execute()) {
        $output['Message'] = "Failed to create an account";
        echo(json_encode($output));
        die();
    }

    $statment->close();
    $connection->close();

    $output['Status'] = "Worked";
    $output['Message'] = "Account registered";
    echo(json_encode($output));
    die();
} else if (isset($_POST['action']) && $_POST['action'] == 'login' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    require_once("$basePath/lib/database.php");
    $connection = databaseConnection($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
    require_once("$basePath/lib/cryptography.php");
    require_once("$basePath/lib/security.php");

    $statment = $connection->prepare("SELECT account_id, password FROM Accounts WHERE username = ?;");
    $statment->bind_param("s", $username);

    if (!$statment->execute()) {
        $output['Message'] = "Failed to check available accounts";
        echo(json_encode($output));
        die();
    }

    $result = $statment->get_result();
    if ($result->num_rows == 0) {
        $output['Message'] = "Invalid account";
        echo(json_encode($output));
        die();
    }

    $row = $result->fetch_assoc();

    if (!passwordVerifiy($password, $row['password'])) {
        $output['Message'] = "Invalid account";
        echo(json_encode($output));
        die();
    }

    $account_id = $row['account_id'];
    $statment->close();
    $connection->close();


    $output['Status'] = "Worked";
    $output['Message'] = JWTEncrypt($JWTPassword, $account_id);
    echo(json_encode($output));
    die();
} else if (isset($_POST['action']) && $_POST['action'] == 'check_jwt' && isset($_POST['token'])) {
    $jwt = $_POST['token'];
    $result = JWTVerifiy($JWTPassword, $jwt);
    if (!$result[0]) {
        $output['Message'] = "Invalid token";
        echo(json_encode($output));
        die();
    }

    $output['Status'] = "Worked";
    $output['Message'] = true;
    echo(json_encode($output));
    die();
}
$output['Message'] = "No valid actions";
echo(json_encode($output));
die();