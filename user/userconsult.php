<?php
include 'db_config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // SQL query to insert data with default 'pending' status
    $sql = "INSERT INTO user (name, email, phone_number, subject, message, status)
            VALUES ('$name', '$email', '$phone_number', '$subject', '$message', 'pending')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to index.php with success status for the enquiry
        header("Location: index.php?status=userconsult_success");
    } else {
        // Redirect to index.php with error status for the enquiry
        header("Location: index.php?status=userconsult_error");
    }
}

// Close the database connection
$conn->close();
?>