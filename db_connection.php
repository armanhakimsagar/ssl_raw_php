<?php

$servername = "localhost";
$username = "czmbdorg_islam"; // Put the MySQL Username
$password = "NBrmG*8(vdwd"; // Put the MySQL Password
$database = "czmbdorg_db_final3"; // Put the Database Name

// Create connection for integration
$conn_integration = mysqli_connect($servername, $username, $password, $database);

// Check connection for integration
if (!$conn_integration) {
    die("Connection failed: " . mysqli_connect_error());
}

