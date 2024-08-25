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
    <title>Add Disease Details</title>
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
            background-color:  #880808;
            color: white;
            border-bottom: 2px solid #0056b3;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color:  #880808;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-control:focus {
            box-shadow: none;
            border-color:  #880808;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h3 class="mb-0">Add Disease Details</h3>
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
                $disease_name = $_POST['disease_name'];
                $disease_properties = $_POST['disease_properties'];
                $disease_symptoms = $_POST['disease_symptoms'];

                // SQL query to insert data into the database
                $sql = "INSERT INTO addDisease (disease_name, properties, symptoms) 
                        VALUES ('$disease_name', '$disease_properties', '$disease_symptoms')";

                if ($conn->query($sql) === TRUE) {
                    echo "<div class='alert alert-success text-center'>New disease details added successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger text-center'>Error: " . $sql . "<br>" . $conn->error . "</div>";
                }

                // Close the database connection
                $conn->close();
            }
            ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="diseaseName" class="form-label">Name of Disease</label>
                    <input type="text" class="form-control" id="diseaseName" name="disease_name" placeholder="Enter the name of the disease" required>
                </div>
                <div class="mb-3">
                    <label for="diseaseProperties" class="form-label">Properties</label>
                    <textarea class="form-control" id="diseaseProperties" name="disease_properties" rows="3" placeholder="Enter the properties of the disease" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="diseaseSymptoms" class="form-label">Symptoms</label>
                    <textarea class="form-control" id="diseaseSymptoms" name="disease_symptoms" rows="3" placeholder="Enter the symptoms of the disease" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
