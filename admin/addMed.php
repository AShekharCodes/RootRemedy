<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 1000px;
        }
        .card {
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #5D3FD3;
            color: white;
            border-bottom: 2px solid #218838;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-success {
            background-color: #5D3FD3;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-outline-success {
            border-color: #5D3FD3;
            color: #5D3FD3;
            transition: background-color 0.3s ease;
        }
        .btn-outline-success:hover {
            background-color: #5D3FD3;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h3 class="mb-0">Add Medicine</h3>
        </div>
        <div class="card-body">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                try {
                    $conn = new PDO("mysql:host=localhost;dbname=rootremedy", "root", "");
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Step 1: Generate a new combination_id
                    $combinationStmt = $conn->prepare("INSERT INTO combination (combination_id) VALUES (NULL)");
                    $combinationStmt->execute();
                    $combination_id = $conn->lastInsertId(); // Get the newly generated combination_id

                    // Step 2: Insert selected plants into the combination_plants table
                    $selectedPlants = $_POST['plant_ids'];
                    foreach ($selectedPlants as $plant_id) {
                        $insertPlantStmt = $conn->prepare("INSERT INTO combination_plants (combination_id, plant_id) VALUES (:combination_id, :plant_id)");
                        $insertPlantStmt->execute([
                            ':combination_id' => $combination_id,
                            ':plant_id' => $plant_id,
                        ]);
                    }

                    // Step 3: Insert the new medicine details into the addMed table
                    $stmt = $conn->prepare("INSERT INTO addMed 
                        (medicine_name, disease_id, preparation_method, how_to_take, category, combination_id) 
                        VALUES (:medicine_name, :disease_id, :preparation_method, :how_to_take, :category, :combination_id)");

                    $stmt->execute([
                        ':medicine_name' => filter_var($_POST['medicine_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        ':disease_id' => filter_var($_POST['disease_id'], FILTER_VALIDATE_INT),
                        ':preparation_method' => filter_var($_POST['preparation_method'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        ':how_to_take' => filter_var($_POST['how_to_take'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        ':category' => filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                        ':combination_id' => $combination_id,
                    ]);

                    echo "<div class='alert alert-success text-center'>New medicine details added successfully!</div>";
                } catch (PDOException $e) {
                    // Debugging: Display error message
                    error_log("Database query failed: " . $e->getMessage());
                    echo "<div class='alert alert-danger text-center'>Error: " . $e->getMessage() . "</div>";
                }
            }
            ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="medicineName" class="form-label">Medicine Name</label>
                    <input type="text" class="form-control" id="medicineName" name="medicine_name" placeholder="Enter the medicine name" required>
                </div>

                <div class="mb-3">
                    <label for="selectDisease" class="form-label">Select Disease</label>
                    <select class="form-select" id="selectDisease" name="disease_id" required>
                        <option value="" disabled selected>Select a disease</option>
                        <?php
                        try {
                            $disease_conn = new PDO("mysql:host=localhost;dbname=rootremedy", "root", "");
                            $disease_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $disease_stmt = $disease_conn->prepare("SELECT disease_id, disease_name FROM addDisease");
                            $disease_stmt->execute();
                            $disease_result = $disease_stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($disease_result as $row) {
                                echo "<option value='" . htmlspecialchars($row['disease_id']) . "'>" . htmlspecialchars($row['disease_name']) . "</option>";
                            }
                        } catch (PDOException $e) {
                            error_log("Database query failed: " . $e->getMessage());
                            echo "<option value='' disabled>Error fetching diseases</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Multiple Select for Plants -->
                <div class="mb-3">
                    <label for="selectPlants" class="form-label">Select Plants</label>
                    <select class="form-select" id="selectPlants" name="plant_ids[]" multiple required>
                        <?php
                        try {
                            $plant_conn = new PDO("mysql:host=localhost;dbname=rootremedy", "root", "");
                            $plant_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $plant_stmt = $plant_conn->prepare("SELECT plant_id, plant_name FROM addPlant");
                            $plant_stmt->execute();
                            $plant_result = $plant_stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($plant_result as $row) {
                                echo "<option value='" . htmlspecialchars($row['plant_id']) . "'>" . htmlspecialchars($row['plant_name']) . "</option>";
                            }
                        } catch (PDOException $e) {
                            error_log("Database query failed: " . $e->getMessage());
                            echo "<option value='' disabled>Error fetching plants</option>";
                        }
                        ?>
                    </select>
                    <small class="form-text text-muted">Hold down the Ctrl (Windows) / Command (Mac) button to select multiple options.</small>
                </div>

                <div class="mb-3">
                    <label for="preparationMethod" class="form-label">Preparation Method</label>
                    <textarea class="form-control" id="preparationMethod" name="preparation_method" rows="3" placeholder="Describe the preparation method" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="howToTake" class="form-label">How to Take</label>
                    <textarea class="form-control" id="howToTake" name="how_to_take" rows="3" placeholder="Instructions on how to take the medicine" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="selectCategory" class="form-label">Select Category</label>
                    <select class="form-select" id="selectCategory" name="category" required>
                        <option value="" disabled selected>Select a category</option>
                        <option value="Herbal">Herbal</option>
                        <option value="Ayurvedic">Ayurvedic</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="reset" class="btn btn-outline-success">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#selectPlants').select2({
            placeholder: "Select plants",
            allowClear: true
        });
    });
</script>
</body>
</html>
