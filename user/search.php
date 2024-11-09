<?php
// Start by checking if the search term was provided
if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $searchQuery = trim($_GET['query']);
    
    include 'db_config.php';

    // Prepare the SQL query for disease based on the search term
    $diseaseStmt = $conn->prepare("SELECT * FROM disease WHERE disease_name LIKE ?");
    $searchTerm = "%" . $searchQuery . "%";
    $diseaseStmt->bind_param("s", $searchTerm);
    $diseaseStmt->execute();
    $diseaseResult = $diseaseStmt->get_result();
    $disease = $diseaseResult->fetch_assoc();

    // Initialize arrays to hold medicines and plants
    $medicines = [];
    $plants = [];

    // Query for Medicines associated with the disease if the disease exists
    if ($disease) {
        // Fetch medicines related to the disease
        $medicineStmt = $conn->prepare("SELECT * FROM medicine WHERE disease_id = ?");
        $medicineStmt->bind_param("i", $disease['disease_id']);
        $medicineStmt->execute();
        $medicineResult = $medicineStmt->get_result();
        while ($row = $medicineResult->fetch_assoc()) {
            $medicines[] = $row;
        }

        // Query for Plants associated with the medicines and disease
        $plantStmt = $conn->prepare(
            "SELECT DISTINCT p.* FROM plant p
             JOIN combination_plants cp ON p.plant_id = cp.plant_id
             JOIN medicine m ON cp.combination_id = m.combination_id
             WHERE m.disease_id = ?"
        );
        $plantStmt->bind_param("i", $disease['disease_id']);
        $plantStmt->execute();
        $plantResult = $plantStmt->get_result();
        while ($row = $plantResult->fetch_assoc()) {
            $plants[] = $row;
        }
    }

    // Close statements if they are initialized
    if (isset($diseaseStmt)) {
        $diseaseStmt->close();
    }
    if (isset($medicineStmt)) {
        $medicineStmt->close();
    }
    if (isset($plantStmt)) {
        $plantStmt->close();
    }

    // Close the connection
    $conn->close();

} else {
    // If no search term, set an error message
    $errorMessage = "Please enter a search term.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <?php if (!empty($errorMessage)): ?>
        <p><?php echo $errorMessage; ?></p>
    <?php else: ?>
        <h2>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
        
        <?php if (!empty($disease)): ?>
            <div class="card-section">
                <h3>Disease Details</h3>
                <div class="card">
                    <h4><?php echo htmlspecialchars($disease['disease_name']); ?></h4>
                    <p>Properties: <?php echo htmlspecialchars($disease['properties']); ?></p>
                    <p>Symptoms: <?php echo htmlspecialchars($disease['symptoms']); ?></p>
                </div>
            </div>

            <!-- Show Medicines for the Disease -->
            <?php if (!empty($medicines)): ?>
                <div class="card-section">
                    <h3>Medicines</h3>
                    <?php foreach ($medicines as $medicine): ?>
                        <div class="card">
                            <h4><?php echo htmlspecialchars($medicine['medicine_name']); ?></h4>
                            <p>Preparation Method: <?php echo htmlspecialchars($medicine['preparation_method']); ?></p>
                            <p>How to Take: <?php echo htmlspecialchars($medicine['how_to_take']); ?></p>
                            <p>Category: <?php echo htmlspecialchars($medicine['category']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Show Plants used in the Medicines -->
            <?php if (!empty($plants)): ?>
                <div class="card-section">
                    <h3>Plants</h3>
                    <?php foreach ($plants as $plant): ?>
                        <div class="card">
                            <h4><?php echo htmlspecialchars($plant['plant_name']); ?></h4>
                            <p>Properties: <?php echo htmlspecialchars($plant['plant_properties']); ?></p>
                            <?php if (!empty($plant['image1'])): ?>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($plant['image1']); ?>" alt="Plant Image" style="width:100%;">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>

        <!-- No Results Found -->
        <?php if (empty($disease) && empty($medicines) && empty($plants)): ?>
            <p>No results found for "<?php echo htmlspecialchars($searchQuery); ?>"</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
