<?php
session_start();
// Database connection
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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subscriber = $_POST['subscribers'];

    $stmt = $conn->prepare("INSERT INTO subscriptions (subscribers) VALUES (?)");
    $stmt->bind_param("s", $subscriber);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'success';
    } else {
        $_SESSION['message'] = 'error';
    }

    $stmt->close();
    $conn->close();

    // Redirect to clear form resubmission and remove URL parameters
    header("Location: index.php");
    exit();
}
