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

// Get POST data
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$category = isset($_POST['category']) ? $_POST['category'] : 'plants';

$tableMap = [
    'plants' => 'addPlant',
    'medicines' => 'addMed',
    'diseases' => 'addDisease'
];

$tableName = $tableMap[$category];
$columnId = ($category == 'plants') ? 'plant_id' : (($category == 'medicines') ? 'medicine_id' : 'disease_id');

// Prepare and execute the delete statement
$delete_sql = "DELETE FROM $tableName WHERE $columnId = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Item deleted successfully!";
} else {
    echo "Error deleting item: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
