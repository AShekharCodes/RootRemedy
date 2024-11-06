<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include 'db_config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];

    // Handling image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Check if entry with the same type already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM featured WHERE type = ?");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Update existing entry
        $stmt = $conn->prepare("UPDATE featured SET title = ?, description = ?, image = ? WHERE type = ?");
        $stmt->bind_param("ssbs", $title, $description, $image, $type);
        if ($stmt->execute()) {
            $message = "<div id='success-message' class='alert alert-success'>Featured item updated successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error updating item: " . $stmt->error . "</div>";
        }
    } else {
        // Insert new entry if it doesn't exist
        $stmt = $conn->prepare("INSERT INTO featured (title, description, image, type) VALUES (?, ?, ?, ?)");
        // Use bind_param to insert image as BLOB
        $stmt->bind_param("ssbs", $title, $description, $image, $type);

        if ($stmt->execute()) {
            $message = "<div id='success-message' class='alert alert-success'>New featured item added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Error inserting item: " . $stmt->error . "</div>";
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add or Update Featured Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 100%;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.25rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <b>Add or Update Featured Item</b>
        </div>
        <div class="card-body">
            <!-- Display message here -->
            <?php echo $message; ?>
            
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Choose Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="plant">Plant</option>
                        <option value="disease">Disease</option>
                        <option value="medicine">Medicine</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Upload</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JavaScript to hide the success message after 3 seconds
    document.addEventListener("DOMContentLoaded", function() {
        const successMessage = document.getElementById("success-message");
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000); // 3000 milliseconds = 3 seconds
        }
    });
</script>
</body>
</html>
