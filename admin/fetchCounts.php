<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";  // Replace with your database username
$password = "";  // Replace with your database password
$dbname = "rootremedy";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch counts
$counts = [
    'plants' => $conn->query("SELECT COUNT(*) AS count FROM addPlant")->fetch_assoc()['count'],
    'medicines' => $conn->query("SELECT COUNT(*) AS count FROM addMed")->fetch_assoc()['count'],
    'diseases' => $conn->query("SELECT COUNT(*) AS count FROM addDisease")->fetch_assoc()['count']
];

$conn->close();

echo json_encode($counts);
?>
