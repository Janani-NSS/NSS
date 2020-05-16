<?php


$conn = new mysqli("localhost", "root", "NSS@123", "dbo");

if ($conn->connect_error) {
    die("ERROR: Unable to connect: " . $conn->connect_error);
}

//echo 'Connected to the database.<br>';
?>