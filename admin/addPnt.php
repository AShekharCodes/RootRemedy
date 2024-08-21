<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Plant Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #28a745;
            color: white;
            border-bottom: 2px solid #218838;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #28a745;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h3 class="mb-0">Add Plant Details</h3>
        </div>
        <div class="card-body">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "rootremedy";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Get form data
                $plant_name = $_POST['plant_name'] ?? '';
                $plant_properties = $_POST['plant_properties'] ?? '';

                // Handle image upload
                $image1 = $image2 = '';

                if (isset($_FILES['plant_image1']) && $_FILES['plant_image1']['error'] == 0) {
                    $image1 = addslashes(file_get_contents($_FILES['plant_image1']['tmp_name']));
                }
                
                if (isset($_FILES['plant_image2']) && $_FILES['plant_image2']['error'] == 0) {
                    $image2 = addslashes(file_get_contents($_FILES['plant_image2']['tmp_name']));
                }

                // SQL query to insert data into the database
                $sql = "INSERT INTO addplant (plant_name, plant_properties, image1, image2) 
                        VALUES ('$plant_name', '$plant_properties', '$image1', '$image2')";

                if ($conn->query($sql) === TRUE) {
                    echo "<div class='alert alert-success text-center'>New plant details added successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger text-center'>Error: " . $sql . "<br>" . $conn->error . "</div>";
                }

                // Close the database connection
                $conn->close();
            }
            ?>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="plantName" class="form-label">Name of the Plant</label>
                    <input type="text" class="form-control" id="plantName" name="plant_name" placeholder="Enter plant name" required>
                </div>
                <div class="mb-3">
                    <label for="plantProperties" class="form-label">Plant Properties</label>
                    <textarea class="form-control" id="plantProperties" name="plant_properties" rows="3" placeholder="Enter plant properties" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="plantImage1" class="form-label">Insert Plant Image 1</label>
                    <input class="form-control" type="file" id="plantImage1" name="plant_image1" required>
                </div>
                <div class="mb-3">
                    <label for="plantImage2" class="form-label">Insert Plant Image 2</label>
                    <input class="form-control" type="file" id="plantImage2" name="plant_image2" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Submit</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
