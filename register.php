<?php

session_start();

$Name = $_POST['name'];
$Email = $_POST['email'];
$Phone_no = $_POST['phone'];
$role=$_POST['role'];
$password = $_POST['password'];
$confirm_Password = $_POST['confirm_password'];

// Establish connection
$conn = mysqli_connect("localhost", "root", "1234", "housing_rental");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO user (name, email_id, phone_no, role, password, confirm_password) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $Name, $Email, $Phone_no, $role, $password, $confirm_Password);

// Execute the statement
if ($stmt->execute()) {
    $_SESSION['message'] = "Registered successfully!";
    header("Location: login.html");
    exit();  // Ensure the script stops here after redirection
} else {
    echo "Error registering landlord: " . $stmt->error;
}
// Close the statement and connection
$stmt->close();
$conn->close();
?>


