<?php
session_start(); // Start the session to use session variables

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rootremedy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect and sanitize input
$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$phone_number = $conn->real_escape_string($_POST['mobile']); // Changed to match form field name
$subject = $conn->real_escape_string($_POST['subject']);
$message = $conn->real_escape_string($_POST['message']);
$status = 'pending'; // Default status

// Insert query
$sql = "INSERT INTO userconsult (name, email, phone_number, subject, message, status)
        VALUES ('$name', '$email', '$phone_number', '$subject', '$message', '$status')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['success'] = "New record created successfully.";
} else {
    $_SESSION['error'] = "Error: " . $conn->error;
}

// Close connection
$conn->close();

// Redirect back to the form page
header("Location: consult.php"); // Change to the actual path of your form page
exit();
?>