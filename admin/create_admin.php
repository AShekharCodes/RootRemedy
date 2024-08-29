<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rootremedy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert admin user
$adminUsername = 'srijanid12@gmail.com';
$adminPassword = password_hash('admin@1234', PASSWORD_DEFAULT);

$sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $adminUsername, $adminPassword);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Admin user created successfully.";
} else {
    echo "Error creating admin user.";
}

$stmt->close();
$conn->close();
?>
