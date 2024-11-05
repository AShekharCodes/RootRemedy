<?php
include 'db_config.php'; // Include your database connection

// Fetch diseases data from the adddisease table
$query = "SELECT disease_id, disease_name FROM disease";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>Serial No.</th><th>Disease Name</th><th>Delete</th></tr></thead><tbody>';
    $serial = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $serial++ . '</td>';
        echo '<td>' . $row['disease_name'] . '</td>';
        echo '<td><button class="btn btn-danger delete-btn" data-id="' . $row['disease_id'] . '" data-type="disease">Delete</button></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p>No diseases found.</p>';
}
?>
