<?php
// Database connection
$servername = "localhost"; // or your server name
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "rootremedy"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // SQL query to insert data with default 'pending' status
    $sql = "INSERT INTO userconsult (name, email, phone_number, subject, message, status)
            VALUES ('$name', '$email', '$phone_number', '$subject', '$message', 'pending')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to form.php with success status
        header("Location: index.php?status=success");
    } else {
        // Redirect to form.php with error status
        header("Location: index.php?status=error");
    }
}

// Close the database connection
$conn->close();
?>