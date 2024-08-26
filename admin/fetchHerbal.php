<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rootremedy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT COUNT(*) as count FROM addMed WHERE category = 'Herbal'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['count'];
} else {
    echo "0";
}

$conn->close();
?>
