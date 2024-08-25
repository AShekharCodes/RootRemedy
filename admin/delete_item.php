<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rootremedy";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the item ID and category from the request
$itemId = isset($_POST['id']) ? intval($_POST['id']) : 0;
$category = isset($_POST['category']) ? $_POST['category'] : 'plants';

// Set the SQL query based on the category
switch ($category) {
    case 'plants':
        $sql = "DELETE FROM addplant WHERE pid = ?";
        break;
    case 'medicines':
        $sql = "DELETE FROM medicine WHERE mid = ?";
        break;
    case 'diseases':
        $sql = "DELETE FROM disease WHERE did = ?";
        break;
    default:
        $sql = "DELETE FROM addplant WHERE pid = ?";
        break;
}

// Prepare and execute the deletion query
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $itemId);
$stmt->execute();
$stmt->close();

$conn->close();
echo "success";
?>
