<?php
include 'db_config.php';

// Handle delete request
if (isset($_POST['delete'])) {
    $table = $_POST['table'];
    $id = $_POST['id'];

    if ($table == 'plant') {
        $query = "DELETE FROM plant WHERE plant_id = ?";
    } elseif ($table == 'disease') {
        $query = "DELETE FROM disease WHERE disease_id = ?";
    } elseif ($table == 'medicine') {
        $query = "DELETE FROM medicine WHERE medicine_id = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting record"]);
    }
    $stmt->close();
    exit(); // Exit after handling AJAX request
}

// Handle data display
$selectedOption = isset($_POST['deleteOption']) ? $_POST['deleteOption'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5" id="containerArea">
    <h2 class="text-center mb-4">Delete Records</h2>

    <!-- Select Option Form -->
    <form method="post" class="mb-4" id="deleteForm">
        <div class="mb-3">
            <label for="deleteOption" class="form-label">Select option to delete:</label>
            <select name="deleteOption" id="deleteOption" class="form-select">
                <option value="">-- Select --</option>
                <option value="plants" <?php if ($selectedOption == 'plants') echo 'selected'; ?>>Delete Plants</option>
                <option value="disease" <?php if ($selectedOption == 'disease') echo 'selected'; ?>>Delete Disease</option>
                <option value="medicine" <?php if ($selectedOption == 'medicine') echo 'selected'; ?>>Delete Medicine</option>
            </select>
        </div>
    </form>

    <!-- Data Display -->
    <div id="dataDisplay">
        <?php if ($selectedOption == 'plants'): ?>
            <h3>Plants List</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Serial No.</th>
                        <th>Plant Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT plant_id, plant_name FROM plant ORDER BY plant_name ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $serial = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$serial}</td>
                                <td>{$row['plant_name']}</td>
                                <td>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='table' value='plant'>
                                        <input type='hidden' name='id' value='{$row['plant_id']}'>
                                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                        $serial++;
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No plants found</td></tr>";
                }
                ?>
                </tbody>
            </table>

        <?php elseif ($selectedOption == 'disease'): ?>
            <h3>Disease List</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Serial No.</th>
                        <th>Disease Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT disease_id, disease_name FROM disease ORDER BY disease_name ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $serial = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$serial}</td>
                                <td>{$row['disease_name']}</td>
                                <td>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='table' value='disease'>
                                        <input type='hidden' name='id' value='{$row['disease_id']}'>
                                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                        $serial++;
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center'>No diseases found</td></tr>";
                }
                ?>
                </tbody>
            </table>

        <?php elseif ($selectedOption == 'medicine'): ?>
            <h3>Medicine List</h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Serial No.</th>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT medicine_id, medicine_name, category FROM medicine ORDER BY medicine_name ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $serial = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$serial}</td>
                                <td>{$row['medicine_name']}</td>
                                <td>{$row['category']}</td>
                                <td>
                                    <form method='post' style='display:inline;'>
                                        <input type='hidden' name='table' value='medicine'>
                                        <input type='hidden' name='id' value='{$row['medicine_id']}'>
                                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                        $serial++;
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No medicines found</td></tr>";
                }
                ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<script src="/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
