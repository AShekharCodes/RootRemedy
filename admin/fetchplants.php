<?php
include 'db_config.php';

$sql = "SELECT COUNT(*) as count FROM plant";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['count'];
} else {
    echo "0";
}

$conn->close();
?>
