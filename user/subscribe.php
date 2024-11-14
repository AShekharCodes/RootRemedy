<?php
include 'db_config.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form input
    $subscriber = mysqli_real_escape_string($conn, $_POST['subscribers']);

    // Prepare an SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO subscriptions (subscribers) VALUES (?)");
    $stmt->bind_param("s", $subscriber);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to index.php with success status for the subscription
        header("Location: index.php?status=subscribe_success");
    } else {
        // Redirect to index.php with error status for the subscription
        header("Location: index.php?status=subscribe_error");
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>