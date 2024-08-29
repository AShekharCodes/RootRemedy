<?php
// get_image.php
$servername = "localhost"; // your database server
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "rootremedy"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id']; // Assume you pass an ID to specify which image to fetch

// Prepare and execute SQL statement
$stmt = $conn->prepare("SELECT image1 FROM addplant WHERE plant_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($image);
$stmt->fetch();
$stmt->close();
$conn->close();

// Set header to serve image
header("Content-Type: image/jpeg");
echo $image;
?>