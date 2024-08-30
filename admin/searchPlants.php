<?php
header('Content-Type: application/json');

try {
    // Establish a connection to the database
    $conn = new PDO("mysql:host=localhost;dbname=rootremedy", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the search query from the request
    $query = isset($_GET['query']) ? trim($_GET['query']) : '';
    
    // Prepare and execute the SQL query to search for plants
    $stmt = $conn->prepare("SELECT plant_id, plant_name FROM addPlant WHERE plant_name LIKE :query ORDER BY plant_name");
    $stmt->execute([':query' => '%' . $query . '%']);
    
    // Fetch all results and encode them as JSON
    $plants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($plants);

} catch (PDOException $e) {
    // Log the error and return an empty array in case of failure
    error_log("Database query failed: " . $e->getMessage());
    echo json_encode([]);
}
?>
