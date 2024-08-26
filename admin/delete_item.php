<?php
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

$id = $_POST['id'];
$category = $_POST['category'];

// Map categories to table names and column IDs
$tableMap = [
    'plants' => 'addplant',
    'medicines' => 'addmed',
    'diseases' => 'adddisease'
];

$tableName = $tableMap[$category];
$columnId = ($category == 'plants') ? 'plant_id' : (($category == 'medicines') ? 'medicine_id' : 'disease_id');

// Prepare and execute delete query
$query = "DELETE FROM $tableName WHERE $columnId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Record deleted successfully";
    } else {
        echo "No record found to delete";
    }
} else {
    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
