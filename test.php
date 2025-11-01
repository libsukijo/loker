<?php
    $host     = "10.10.120.1";
    $username = "pustaka";
    $password = "pustak@2020";
    $database = "apindb28012012";

    // Connect to Database
    $mysqli = mysqli_connect($host, $username, $password, $database);

    // Check Connection
    if (!$mysqli) {
    // Connection Failed Log
    die('FAILED Connect to Database : ' . mysqli_connect_error());
    }
?>
