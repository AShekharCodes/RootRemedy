<?php
// Database connection
$servername = "localhost"; // or your server name
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "rootremedy"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for each type
$featuredPlant = [];
$featuredDisease = [];
$featuredMedicine = [];

// Fetch featured items
$sql = "SELECT title, description, image, type FROM featured";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        switch ($row['type']) {
            case 'plant':
                $featuredPlant = $row; // Store plant data
                break;
            case 'disease':
                $featuredDisease = $row; // Store disease data
                break;
            case 'medicine':
                $featuredMedicine = $row; // Store medicine data
                break;
        }
    }
}

// Close the connection
$conn->close();
?>