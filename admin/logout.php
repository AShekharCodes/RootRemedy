<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['admin_logged_in'])) {
    // Destroy the session to log out the user
    session_destroy();

    // Redirect to the login page
    header('Location: login.php');
    exit();
} else {
    // If no session is found, redirect to the login page
    header('Location: login.php');
    exit();
}
?>
