<?php
session_start();
include 'db_config.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !isset($_SESSION['admin_username'])) {
    header('Location: admin_login.php');
    exit();
}

$admin_username = $_SESSION['admin_username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Activity Log</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Admin Activity Log</h2>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>Log ID</th>
                <th>Admin Username</th>
                <th>Activity</th>
                <th>Activity Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Retrieve activity log entries for the current admin
            $logQuery = "SELECT log_id, admin_username, activity, activity_time FROM admin_activity_log WHERE admin_username = ? ORDER BY activity_time DESC";
            $stmt = $conn->prepare($logQuery);
            $stmt->bind_param("s", $admin_username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['log_id']}</td>
                            <td>{$row['admin_username']}</td>
                            <td>{$row['activity']}</td>
                            <td>{$row['activity_time']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No activity recorded.</td></tr>";
            }
            
            $stmt->close();
            ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
