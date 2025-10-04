<?php
$servername = "localhost"; // Your database server
$username = "root";        // Your database username
$password = "1234";            // Your database password
$dbname = "housing_rental";    // Your database name

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>