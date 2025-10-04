<?php
session_start(); // Start the session to store the login message

$Email = $_POST['email'];
$Password = $_POST['Password'];

include "conn.php";

$stmt = $conn->prepare("SELECT password FROM user WHERE email_id = ?");
$stmt->bind_param("s", $Email);
$stmt->execute();
$result = $stmt->get_result();

// Check if a user was found
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedPassword = $row['password'];

    // Compare the input password with the stored password
    if ($Password === $storedPassword) {
        // Set session message
        $_SESSION['message'] = "Login Successfully";
        
        // Redirect to the index page with the message in the URL
        header("Location: main.html?message=Login%20Successfully");
        exit; // Make sure no further code runs after redirect
    } else {
        $_SESSION['message'] = "Invalid password";
        header("Location: login.php?message=Invalid%20password");
        exit;
    }
} else {
    $_SESSION['message'] = "No user found with that email";
    header("Location: login.php?message=No%20user%20found%20with%20that%20email");
    exit;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
