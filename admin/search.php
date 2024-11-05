<?php
include 'db_config.php'; // Include your database connection

// Get the search query from the POST request
$query = isset($_POST['query']) ? trim($_POST['query']) : '';

// Initialize the output variable
$output = '';

// Check if the query is not empty
if ($query !== '') {
    // Create a search query for multiple tables
    $output .= '<h3>Search Results for "' . htmlspecialchars($query) . '"</h3>';
    
    // Search in plants table
    $sqlPlants = "SELECT plant_id, plant_name FROM plant WHERE plant_name LIKE ?";
    $stmtPlants = $conn->prepare($sqlPlants);
    $searchTerm = "%" . $query . "%";
    $stmtPlants->bind_param("s", $searchTerm);
    $stmtPlants->execute();
    $resultPlants = $stmtPlants->get_result();
    
    if ($resultPlants->num_rows > 0) {
        $output .= '<h4>Plants:</h4><table class="table table-striped">';
        $output .= '<thead><tr><th>Serial No.</th><th>Plant Name</th><th>Delete</th></tr></thead><tbody>';
        $serial = 1;
        while ($row = $resultPlants->fetch_assoc()) {
            $output .= '<tr>';
            $output .= '<td>' . $serial++ . '</td>';
            $output .= '<td>' . htmlspecialchars($row['plant_name']) . '</td>';
            $output .= '<td><button class="btn btn-danger delete-btn" data-id="' . $row['plant_id'] . '" data-type="plant">Delete</button></td>';
            $output .= '</tr>';
        }
        $output .= '</tbody></table>';
    }

    // Search in diseases table
    $sqlDiseases = "SELECT disease_id, disease_name FROM disease WHERE disease_name LIKE ?";
    $stmtDiseases = $conn->prepare($sqlDiseases);
    $stmtDiseases->bind_param("s", $searchTerm);
    $stmtDiseases->execute();
    $resultDiseases = $stmtDiseases->get_result();
    
    if ($resultDiseases->num_rows > 0) {
        $output .= '<h4>Diseases:</h4><table class="table table-striped">';
        $output .= '<thead><tr><th>Serial No.</th><th>Disease Name</th><th>Delete</th></tr></thead><tbody>';
        $serial = 1;
        while ($row = $resultDiseases->fetch_assoc()) {
            $output .= '<tr>';
            $output .= '<td>' . $serial++ . '</td>';
            $output .= '<td>' . htmlspecialchars($row['disease_name']) . '</td>';
            $output .= '<td><button class="btn btn-danger delete-btn" data-id="' . $row['disease_id'] . '" data-type="disease">Delete</button></td>';
            $output .= '</tr>';
        }
        $output .= '</tbody></table>';
    }

    // Search in medicines table
    $sqlMedicines = "SELECT medicine_id, medicine_name FROM medicine WHERE medicine_name LIKE ?";
    $stmtMedicines = $conn->prepare($sqlMedicines);
    $stmtMedicines->bind_param("s", $searchTerm);
    $stmtMedicines->execute();
    $resultMedicines = $stmtMedicines->get_result();
    
    if ($resultMedicines->num_rows > 0) {
        $output .= '<h4>Medicines:</h4><table class="table table-striped">';
        $output .= '<thead><tr><th>Serial No.</th><th>Medicine Name</th><th>Delete</th></tr></thead><tbody>';
        $serial = 1;
        while ($row = $resultMedicines->fetch_assoc()) {
            $output .= '<tr>';
            $output .= '<td>' . $serial++ . '</td>';
            $output .= '<td>' . htmlspecialchars($row['medicine_name']) . '</td>';
            $output .= '<td><button class="btn btn-danger delete-btn" data-id="' . $row['medicine_id'] . '" data-type="medicine">Delete</button></td>';
            $output .= '</tr>';
        }
        $output .= '</tbody></table>';
    }

    // Check if no results were found in all tables
    if ($resultPlants->num_rows === 0 && $resultDiseases->num_rows === 0 && $resultMedicines->num_rows === 0) {
        $output .= '<p>No results found for "' . htmlspecialchars($query) . '"</p>';
    }
} else {
    $output .= '<p>Please enter a search term.</p>';
}

// Return the output
echo $output;
?>
