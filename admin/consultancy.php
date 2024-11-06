<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Include the database configuration file
require_once 'db_config.php';

// Fetch consultancy requests from the database where the status is 'Pending'
$sql = "SELECT user_id, name, email, phone_number, subject, message, status FROM user WHERE status = 'Pending'";
$result = $conn->query($sql);

// Initialize a variable for displaying messages
$message = '';

// Handle resolving consultancy requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $action = 'Resolved';

    if ($user_id > 0) {
        // Update the status in the user table
        $update_sql = "UPDATE user SET status = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $action, $user_id);

        if ($update_stmt->execute()) {
            // After marking the request as Resolved, refresh the page
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    } else {
        $message = "Invalid input. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Remedy Admin - Consultancy Requests</title>
    <!-- Include Bootstrap 5 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            background-color: #f8f9fa;
        }
        .container{
            max-width: 100%;
        }
        .table-container {
            margin-top: 20px;
        }
        .page-title h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2rem;
            color: #495057;
        }
        .table-container .alert {
            margin-bottom: 20px;
        }
        .action-buttons form {
            display: inline-block;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="table-container card shadow-sm p-4">
        <div class="page-title">
            <h1>Consultancy Requests</h1>
        </div>
        <!-- Display any messages -->
        <?php if ($message): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row['subject'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td>" . htmlspecialchars($row['message'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                            echo "<td><span class='badge bg-warning text-dark'>" . htmlspecialchars($row['status'] ?? '', ENT_QUOTES, 'UTF-8') . "</span></td>";
                            echo "<td class='action-buttons'>";
                            // Form for Resolved action only
                            echo "<form method='post' class='consultancy-form' style='display:inline-block;'>";
                            echo "<input type='hidden' name='user_id' value='" . intval($row['user_id']) . "'>";
                            echo "<button class='btn btn-success btn-sm' type='submit'>Resolve</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No consultancy requests found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Include Bootstrap 5 JS and Popper.js -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
