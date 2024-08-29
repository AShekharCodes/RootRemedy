<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

include 'db_config.php';

// Determine the category
$category = isset($_GET['category']) ? $_GET['category'] : 'plants';
$tableMap = [
    'plants' => 'addPlant',
    'medicines' => 'addMed',
    'diseases' => 'addDisease'
];

$tableName = $tableMap[$category];
$columnId = ($category == 'plants') ? 'plant_id' : (($category == 'medicines') ? 'medicine_id' : 'disease_id');
$nameColumn = ($category == 'plants') ? 'plant_name' : (($category == 'medicines') ? 'medicine_name' : 'disease_name');
$propertiesColumn = ($category == 'plants') ? 'plant_properties' : (($category == 'medicines') ? 'preparation_method' : 'properties');
$symptomsColumn = ($category == 'medicines') ? 'how_to_take' : null; // Only for medicines

// Fetch data
$query = "SELECT $columnId, $nameColumn, $propertiesColumn" . ($symptomsColumn ? ", $symptomsColumn" : "") . " FROM $tableName";
$result = $conn->query($query);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Remedy Admin - Delete Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>


<div class="container mt-5">
    <h1 class="mb-4">Delete Items</h1>

    <div class="mb-4">
        <label for="item-select" class="form-label">Select Category:</label>
        <select id="item-select" class="form-select" onchange="updateTable()">
            <option value="plants" <?= $category == 'plants' ? 'selected' : '' ?>>Plants</option>
            <option value="medicines" <?= $category == 'medicines' ? 'selected' : '' ?>>Medicines</option>
            <option value="diseases" <?= $category == 'diseases' ? 'selected' : '' ?>>Diseases</option>
        </select>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Properties</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row[$nameColumn]) ?></td>
                    <td><?= htmlspecialchars($row[$propertiesColumn] ?? 'No properties') ?></td>
                    <?php if ($category == 'medicines'): ?>
                        <td><?= htmlspecialchars($row[$symptomsColumn] ?? 'No symptoms') ?></td>
                    <?php endif; ?>
                    <td><button class="btn btn-danger" onclick="showModal(<?= $row[$columnId] ?>)">Delete</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var itemIdToDelete;
    var categoryToDelete;

    function updateTable() {
        var category = document.getElementById('item-select').value;
        window.location.href = 'delete.php?category=' + category;
    }

    function showModal(id) {
        itemIdToDelete = id;
        categoryToDelete = document.getElementById('item-select').value;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    function confirmDelete() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'deleteItem.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText);
                updateTable();
            } else {
                alert('Error deleting record.');
            }
        };
        xhr.send('id=' + encodeURIComponent(itemIdToDelete) + '&category=' + encodeURIComponent(categoryToDelete));
        var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
        deleteModal.hide();
    }
</script>

</body>
</html>
