<?php
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "";     // Your MySQL password
$dbname = "foodiebayan";

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
