<?php
include 'db_config.php'; // Include your database connection

// Fetch medicines data from the addmed table
$query = "SELECT medicine_id, medicine_name, category FROM medicine";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>Serial No.</th><th>Medicine Name</th><th>Category</th><th>Delete</th></tr></thead><tbody>';
    $serial = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $serial++ . '</td>';
        echo '<td>' . $row['medicine_name'] . '</td>';
        echo '<td>' . $row['category'] . '</td>';
        echo '<td><button class="btn btn-danger delete-btn" data-id="' . $row['medicine_id'] . '" data-type="medicine">Delete</button></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p>No medicines found.</p>';
}
?>
