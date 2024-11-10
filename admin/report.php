<?php
// Enable error reporting for debugging (remove this in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
include 'db_config.php';

// Fetch table data and return an array
function fetchTableData($conn, $table_name) {
    $data = [];
    $sql = "SELECT * FROM $table_name";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

// Download data as CSV or Excel
if (isset($_GET['export']) && isset($_GET['format'])) {
    $table = $_GET['export'];
    $format = $_GET['format'];
    $data = fetchTableData($conn, $table);
    $filename = $table . '_report';

    if ($format == 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        $output = fopen('php://output', 'w');
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0])); // Write header
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        fclose($output);
        exit();
    } elseif ($format == 'excel') {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        echo '<table border="1">';
        if (!empty($data)) {
            echo '<tr>';
            foreach (array_keys($data[0]) as $key) {
                echo "<th>$key</th>";
            }
            echo '</tr>';
            foreach ($data as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo "<td>$cell</td>";
                }
                echo '</tr>';
            }
        }
        echo '</table>';
        exit();
    }
}

// Fetch data for display
$plant_data = fetchTableData($conn, 'plant');
$medicine_data = fetchTableData($conn, 'medicine');
$disease_data = fetchTableData($conn, 'disease');
$userconsult_data = fetchTableData($conn, 'user');

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Report - Root Remedy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #28a745;
            color: white;
        }
        .download-buttons {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Root Remedy Database Report</h2>

    <!-- Add Plant Table -->
    <h3>Table: Plants Data</h3>
    <div class="download-buttons">
        <a href="report.php?export=plant&format=csv" class="btn btn-success">Download as CSV</a>
        <a href="report.php?export=plant&format=excel" class="btn btn-primary">Download as Excel</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Plant ID</th>
                <th>Plant Name</th>
                <th>Plant Properties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($plant_data as $row): ?>
                <tr>
                    <td><?= $row['plant_id'] ?></td>
                    <td><?= $row['plant_name'] ?></td>
                    <td><?= $row['plant_properties'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add Medicine Table -->
    <h3>Table: Medicines Data</h3>
    <div class="download-buttons">
        <a href="report.php?export=medicine&format=csv" class="btn btn-success">Download as CSV</a>
        <a href="report.php?export=medicine&format=excel" class="btn btn-primary">Download as Excel</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Medicine ID</th>
                <th>Medicine Name</th>
                <th>Disease ID</th>
                <th>Preparation Method</th>
                <th>How to Take</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($medicine_data as $row): ?>
                <tr>
                    <td><?= $row['medicine_id'] ?></td>
                    <td><?= $row['medicine_name'] ?></td>
                    <td><?= $row['disease_id'] ?></td>
                    <td><?= $row['preparation_method'] ?></td>
                    <td><?= $row['how_to_take'] ?></td>
                    <td><?= $row['category'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add Disease Table -->
    <h3>Table: Diseases Data</h3>
    <div class="download-buttons">
        <a href="report.php?export=disease&format=csv" class="btn btn-success">Download as CSV</a>
        <a href="report.php?export=disease&format=excel" class="btn btn-primary">Download as Excel</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Disease ID</th>
                <th>Disease Name</th>
                <th>Properties</th>
                <th>Symptoms</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($disease_data as $row): ?>
                <tr>
                    <td><?= $row['disease_id'] ?></td>
                    <td><?= $row['disease_name'] ?></td>
                    <td><?= $row['properties'] ?></td>
                    <td><?= $row['symptoms'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- User Consultancy Table -->
    <h3>Table: User Consultation Records</h3>
    <div class="download-buttons">
        <a href="report.php?export=user&format=csv" class="btn btn-success">Download as CSV</a>
        <a href="report.php?export=user&format=excel" class="btn btn-primary">Download as Excel</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($userconsult_data as $row): ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['phone_number'] ?></td>
                    <td><?= $row['subject'] ?></td>
                    <td><?= $row['message'] ?></td>
                    <td><?= $row['status'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
