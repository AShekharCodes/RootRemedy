<?php
include 'db_config.php'; // Include your database connection

if (isset($_POST['id']) && isset($_POST['type'])) {
    $id = intval($_POST['id']);
    $type = $_POST['type'];
    
    if ($type === 'plant') {
        $query = "DELETE FROM plant WHERE plant_id = ?";
    } elseif ($type === 'disease') {
        $query = "DELETE FROM disease WHERE disease_id = ?";
    } elseif ($type === 'medicine') {
        $query = "DELETE FROM medicine WHERE medicine_id = ?";
    } else {
        echo 'Invalid type';
        exit;
    }
    
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if (mysqli_stmt_execute($stmt)) {
            echo ucfirst($type) . ' deleted successfully.';
        } else {
            echo 'Error deleting ' . $type;
        }
        mysqli_stmt_close($stmt);
    } else {
        echo 'Failed to prepare statement';
    }
} else {
    echo 'Invalid request';
}

mysqli_close($conn);
?>
