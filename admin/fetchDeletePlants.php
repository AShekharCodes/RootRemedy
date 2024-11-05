<?php
include 'db_config.php'; // Include your database connection

// Fetch plants data from the addplant table
$query = "SELECT plant_id, plant_name FROM plant";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo '<table class="table table-striped">';
    echo '<thead><tr><th>Serial No.</th><th>Plant Name</th><th>Delete</th></tr></thead><tbody>';
    $serial = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $serial++ . '</td>';
        echo '<td>' . $row['plant_name'] . '</td>';
        echo '<td><button class="btn btn-danger delete-btn" data-id="' . $row['plant_id'] . '" data-type="plant">Delete</button></td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p>No plants found.</p>';
}
?>
