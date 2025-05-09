<?php

require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/env.php");
$output = ["Status" => "Failed", "Message" => ""];
if (isset($_POST['action']) && $_POST['action'] == 'list_notes' && isset($_POST['token'])) {
    require_once("$basePath/lib/cryptography.php");
    require_once("$basePath/lib/security.php");
    $token = $_POST['token'];
    $jwtResult = JWTVerifiy($JWTPassword, $token);
    if (!$jwtResult[0]) {
        $output['Message'] = "Invalid token";
        echo(json_encode($output));
        die();
    }

    $account_id = $jwtResult[1];
    require_once("$basePath/lib/database.php");
    $connection = databaseConnection($databaseHostname, $databaseUsername, $databasePassword, $databaseName);
    $statment = $connection->prepare("SELECT title, content FROM Notes WHERE account_id = ?;");
    $statment->bind_param("i", $account_id);

    if (!$statment->execute()) {
        $output['Message'] = "Failed to check available notes";
        echo(json_encode($output));
        die();
    }

    $result = $statment->get_result();
    $output['Message'] = [];
    if ($result->num_rows == 0) {
        echo(json_encode($output));
        die();
    }

    while ($row = $result->fetch_assoc()) {
        $output['Message'][] = $row;
    }

    $statment->close();
    $connection->close();
    $output['Status'] = "Worked";
    echo(json_encode($output));
    die();
}

$output['Message'] = "No valid actions";
echo(json_encode($output));
die();