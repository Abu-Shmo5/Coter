<?php
require_once($_SERVER['DOCUMENT_ROOT']."/api/lib/env.php");

if (!isset($_POST['password']) || $_POST['password'] != $setupPassword) {
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
require_once("$basePath/lib/database.php");
$result = new Result("Database");
$connection = databaseConnection($databaseHostname, $databaseUsername, $databasePassword);

if ($connection->query("DROP DATABASE IF EXISTS $databaseName") === TRUE) {
  $result->works("Database dropped successfully");
} else {
  $result->fails("Error dropping database: " . $connection->error);
}
  

if ($connection->query("CREATE DATABASE $databaseName") === TRUE) {
  $result->works("Database created successfully");
} else {
  $result->fails("Error creating database: " . $connection->error);
}

$connection->close();

$connection = databaseConnection($databaseHostname, $databaseUsername, $databasePassword, $databaseName);

require_once("$basePath/lib/security.php");

if ($connection->query("CREATE TABLE Accounts (
  account_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username TEXT NOT NULL,
  password TEXT NOT NULL
  );") === TRUE) {
  $result->works("Table Accounts created successfully");
} else {
  $result->fails("Error creating table: " . $connection->error);
}

if (!$production) {
  $statment = $connection->prepare("INSERT INTO Accounts (username, password) VALUES (?, ?);");
  $statment->bind_param("ss", $username, $password);

  $username = "demo";
  $password = passwordEncryption("demo");
  if ($statment->execute()) {
    $result->works("Demo account created successfully");
  } else {
    $result->fails("Demo account failed to create");
  }
  $demo_account_id = $statment->insert_id;
  $statment->close();
}

if ($connection->query("CREATE TABLE Notes (
  note_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  account_id INT UNSIGNED,
  title TEXT NOT NULL,
  content TEXT NOT NULL,
  FOREIGN KEY (account_id) REFERENCES Accounts(account_id)
  );") === TRUE) {
  $result->works("Table Notes created successfully");
} else {
  $result->fails("Error creating table: " . $connection->error);
}

if (!$production) {
  $statment = $connection->prepare("INSERT INTO Notes (account_id, title, content) VALUES (?, ?, ?);");
  $statment->bind_param("sss", $demo_account_id, $title, $content);

  $title = "Plaintext";
  $content = "Hello There";
  if ($statment->execute()) {
    $result->works("Plaintext note created successfully");
  } else {
    $result->fails("Plaintext note failed to create");
  }

  $title = "Markdown";
  $content = "# Hello There";
  if ($statment->execute()) {
    $result->works("Markdown note created successfully");
  } else {
    $result->fails("Markdown note failed to create");
  }
  $statment->close();
}

$connection->close();
$result->printResult();

?>
</body>
</html>