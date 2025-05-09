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

require_once("$basePath/lib/database.php");
$connection = databaseConnection($databaseHostname, $databaseUsername, $databasePassword);

if ($connection->query("DROP DATABASE IF EXISTS $databaseName") === TRUE) {
    echo("Database dropped successfully<br />");
  } else {
    echo("Error dropping database: " . $connection->error."<br />");
  }
  

if ($connection->query("CREATE DATABASE $databaseName") === TRUE) {
  echo("Database created successfully<br />");
} else {
  echo("Error creating database: " . $connection->error."<br />");
}

$connection->close();

$connection = databaseConnection($databaseHostname, $databaseUsername, $databasePassword, $databaseName);

require_once("$basePath/lib/security.php");

if ($connection->query("CREATE TABLE Accounts (
  account_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username TEXT NOT NULL,
  password TEXT NOT NULL
  );") === TRUE) {
  echo "Table Accounts created successfully<br />";
} else {
  echo "Error creating table: " . $connection->error."<br />";
}

if (!$production) {
  $statment = $connection->prepare("INSERT INTO Accounts (username, password) VALUES (?, ?);");
  $statment->bind_param("ss", $username, $password);

  $username = "demo";
  $password = passwordEncryption("demo");
  if ($statment->execute()) {
    echo("Demo account created successfully<br />");
  } else {
    echo("Demo account failed to create<br />");
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
  echo "Table Notes created successfully<br />";
} else {
  echo "Error creating table: " . $connection->error."<br />";
}

if (!$production) {
  $statment = $connection->prepare("INSERT INTO Notes (account_id, title, content) VALUES (?, ?, ?);");
  $statment->bind_param("sss", $demo_account_id, $title, $content);

  $title = "Plaintext";
  $content = "Hello There";
  if ($statment->execute()) {
    echo("Plaintext note created successfully<br />");
  } else {
    echo("Plaintext note failed to create<br />");
  }

  $title = "Markdown";
  $content = "# Hello There";
  if ($statment->execute()) {
    echo("Markdown note created successfully<br />");
  } else {
    echo("Markdown note failed to create<br />");
  }
  $statment->close();
}

$connection->close();