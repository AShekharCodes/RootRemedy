<?php
include 'db_config.php';


// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form input
    $subscriber = $_POST['subscribers'];

    // Prepare an SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO subscriptions (subscribers) VALUES (?)");
    $stmt->bind_param("s", $subscriber);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: index.php?status=success");
    } else {
        header("Location: index.php?status=error");
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>