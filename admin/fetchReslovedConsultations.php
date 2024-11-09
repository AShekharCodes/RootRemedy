<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

include 'db_config.php';

// Count consultation requests
$sql = "SELECT COUNT(*) AS count FROM user where status = 'resolved'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['count'];
} else {
    echo 0;
}

$conn->close();
?>
