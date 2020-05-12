<?php


$conn = new mysqli("localhost", "root", "NSS@123", "id13357026_saravanasupermarket");

if ($conn->connect_error) {
    die("ERROR: Unable to connect: " . $conn->connect_error);
}

//echo 'Connected to the database.<br>';
?>