<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";  // Replace with your database username
$password = "";  // Replace with your database password
$dbname = "rootremedy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Count consultation requests
$sql = "SELECT COUNT(*) AS count FROM userconsult";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['count'];
} else {
    echo 0;
}

$conn->close();
?>
