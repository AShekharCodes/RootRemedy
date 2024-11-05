<?php
header('Content-Type: application/json');
include 'db_config.php';
// Fetch counts
$counts = [
    'plants' => $conn->query("SELECT COUNT(*) AS count FROM plant")->fetch_assoc()['count'],
    'medicines' => $conn->query("SELECT COUNT(*) AS count FROM medicine")->fetch_assoc()['count'],
    'diseases' => $conn->query("SELECT COUNT(*) AS count FROM disease")->fetch_assoc()['count'],
    'ayurvedic' => $conn->query("SELECT COUNT(*) AS count FROM medicine WHERE category = 'Ayurvedic'")->fetch_assoc()['count'],
    'herbal' => $conn->query("SELECT COUNT(*) AS count FROM medicine WHERE category = 'Herbal'")->fetch_assoc()['count']
];

$conn->close();

echo json_encode($counts);
?>
