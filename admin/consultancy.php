<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Include the database configuration file
require_once 'db_config.php';

// Fetch consultancy requests from the database
$sql = "SELECT user_id, name, email, phone_number, subject, message, status FROM userconsult";
$result = $conn->query($sql);

// Initialize a variable for displaying messages
$message = '';

// Update the status of a consultancy request if an action is taken
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $new_status = isset($_POST['status']) ? htmlspecialchars(trim($_POST['status'])) : '';

    // Prepare and execute the update statement
    if ($user_id > 0 && ($new_status === 'Resolved' || $new_status === 'Rejected')) {
        $update_sql = "UPDATE userconsult SET status = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $new_status, $user_id);

        if ($stmt->execute()) {
            $message = "Consultancy request updated successfully!";
            // Refresh the page to reflect the update
            header('Location: consultancy.php');
            exit();
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    } else {
        $message = "Invalid input. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Remedy Admin - Consultancy Requests</title>
    <!-- Include Bootstrap 5 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        .table-container {
            margin-top: 50px;
            border: 1px solid #007BFF; /* Blue border */
            border-radius: 8px;
            background-color: #f8f9fa; /* Light background */
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.1); /* Blue shadow */
        }
        .table thead {
            background-color: #007BFF; /* Darker blue for header */
            color: #fff;
        }
        .table tbody tr {
            border-bottom: 1px solid #dfe4ea; /* Light border for rows */
        }
        .table tbody tr:last-child {
            border-bottom: none;
        }
        .badge {
            padding: 8px 12px; /* Uniform badge size */
        }
        .action-buttons button {
            width: 100px; /* Same width for buttons */
            margin: 5px 0; /* Space between buttons */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="table-container">
        <div class="page-title">
            <h1>Consultancy Requests</h1>
        </div>

        <!-- Display any messages -->
        <?php if ($message): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['email'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['subject'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['message'] ?? '', ENT_QUOTES, 'UTF-8') . "</td>";

                        // Determine status class for styling
                        $status_class = "badge bg-warning text-dark";
                        if ($row['status'] === 'Resolved') {
                            $status_class = "badge bg-success";
                        } elseif ($row['status'] === 'Rejected') {
                            $status_class = "badge bg-danger";
                        }

                        echo "<td><span class='$status_class'>" . htmlspecialchars($row['status'] ?? '', ENT_QUOTES, 'UTF-8') . "</span></td>";
                        echo "<td class='action-buttons'>";

                        if ($row['status'] === 'Pending') {
                            echo "<form method='post' style='display:inline;'>";
                            echo "<input type='hidden' name='user_id' value='" . intval($row['user_id']) . "'>";
                            echo "<button class='btn btn-success btn-sm' type='submit' name='status' value='Resolved'>Resolve</button> ";
                            echo "<button class='btn btn-danger btn-sm' type='submit' name='status' value='Rejected'>Reject</button>";
                            echo "</form>";
                        } else {
                            echo "<button class='btn btn-success btn-sm' disabled>Resolve</button> ";
                            echo "<button class='btn btn-danger btn-sm' disabled>Reject</button>";
                        }

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No consultancy requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Include Bootstrap 5 JS and Popper.js -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
