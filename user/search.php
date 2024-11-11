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
        // Fetch medicines related to the disease along with plant names
        $medicineStmt = $conn->prepare("
            SELECT m.medicine_name, m.preparation_method, m.how_to_take, m.category, 
                   GROUP_CONCAT(p.plant_name SEPARATOR ', ') AS plant_names
            FROM medicine m
            LEFT JOIN combination_plants cp ON m.combination_id = cp.combination_id
            LEFT JOIN plant p ON cp.plant_id = p.plant_id
            WHERE m.disease_id = ?
            GROUP BY m.medicine_id
        ");
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
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="shortcut icon" href="assets/favicon.png" type="png" />
  <title>Recommendation Results</title>

  <!-- Bootstrap core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet" />
  
  <!-- Font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- Responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <style>
        h3 {
            color: white;
        }
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            background-image: url('assets/featured_bg.jpg');
            background-size: cover;
            background-attachment: fixed;
        }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg custom_nav-container">
    <a class="navbar-brand" href="index.php">
        <span>
            <img src="assets/logo.png" width="250px" />
        </span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="filteredsearch.php">Filtered Search</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.html">Get in touch</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <?php if (!empty($errorMessage)): ?>
        <p class="alert alert-danger"><?php echo $errorMessage; ?></p>
    <?php else: ?>
        <h2>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
        
        <?php if (!empty($disease)): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h3>Disease Details</h3>
                    <h4><?php echo htmlspecialchars($disease['disease_name']); ?></h4>
                    <p><strong>Properties:</strong> <?php echo htmlspecialchars($disease['properties']); ?></p>
                    <p><strong>Symptoms:</strong> <?php echo htmlspecialchars($disease['symptoms']); ?></p>
                </div>
            </div>

            <!-- Show Medicines for the Disease -->
            <?php if (!empty($medicines)): ?>
                <div class="card-section mb-4">
                    <h3>Medicines</h3>
                    <?php foreach ($medicines as $medicine): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h4><?php echo htmlspecialchars($medicine['medicine_name']); ?></h4>
                                <p><strong>Preparation Method:</strong> <?php echo htmlspecialchars($medicine['preparation_method']); ?></p>
                                <p><strong>How to Take:</strong> <?php echo htmlspecialchars($medicine['how_to_take']); ?></p>
                                <p><strong>Category:</strong> <?php echo htmlspecialchars($medicine['category']); ?></p>
                                <p><strong>Plants Used:</strong> <?php echo htmlspecialchars($medicine['plant_names']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Show Plants used in the Medicines -->
            <?php if (!empty($plants)): ?>
                <div class="card-section">
                    <h3>Plants</h3>
                    <?php foreach ($plants as $plant): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h4><?php echo htmlspecialchars($plant['plant_name']); ?></h4>
                                <p><strong>Properties:</strong> <?php echo htmlspecialchars($plant['plant_properties']); ?></p>
                                <?php if (!empty($plant['image1'])): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($plant['image1']); ?>" alt="Plant Image" class="img-fluid">
                                <?php endif; ?>
                            </div>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
