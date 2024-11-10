<?php
session_start();
include 'db_config.php'; // Include database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php'); // Redirect if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validate inputs
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo "All fields are required.";
    } elseif ($newPassword !== $confirmPassword) {
        echo "New password and confirm password do not match.";
    } else {
        // Fetch current password hash from the database
        $adminUsername = $_SESSION['admin_username']; // Assuming username is stored in session
        $query = "SELECT password FROM admin WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $adminUsername);
        $stmt->execute();
        $stmt->bind_result($storedPasswordHash);
        $stmt->fetch();
        $stmt->close();

        // Verify current password
        if (!password_verify($currentPassword, $storedPasswordHash)) {
            echo "Current password is incorrect.";
        } else {
            // Hash the new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

            // Update the password in the database
            $updateQuery = "UPDATE admin SET password = ? WHERE username = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ss", $newPasswordHash, $adminUsername);

            if ($updateStmt->execute()) {
                echo "Password changed successfully.";
            } else {
                echo "Error updating password.";
            }
            $updateStmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Change Password</h2>
    <form method="POST" action="changepass.php">
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>
</body>
</html>
