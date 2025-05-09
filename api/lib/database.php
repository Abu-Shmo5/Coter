<?php

function databaseConnection($hostname, $username, $password, $databaseName="") {
    if (empty($databaseName)) { $connection = new mysqli($hostname, $username, $password); } 
    else { $connection = new mysqli($hostname, $username, $password, $databaseName); }
    if ($connection->connect_error) { die("Failed to connect to database"); }
    return $connection;
}