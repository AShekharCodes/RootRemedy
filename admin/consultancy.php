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
    <title>Root Remedy Admin - Consultancy Requests</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        header {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 15px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        header nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header nav a {
            color: #ecf0f1;
            text-decoration: none;
            margin: 0 15px;
        }
        header nav a:hover {
            text-decoration: underline;
        }
        .content {
            padding-top: 100px;
            max-width: 1200px;
            margin: auto;
        }
        .page-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .page-title h1 {
            color: #34495e;
            font-size: 2.5em;
        }
        .consultancy-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .consultancy-table th, .consultancy-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .consultancy-table th {
            background-color: #3498db;
            color: white;
        }
        .consultancy-table td {
            background-color: white;
            color: #34495e;
        }
        .status-resolved {
            color: green;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
        }
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .action-buttons button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
            font-size: 1em;
        }
        .resolve-btn {
            background-color: #27ae60;
            color: white;
        }
        .reject-btn {
            background-color: #e74c3c;
            color: white;
        }
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 20px;
        }
    </style>
</head>
<body>



<div class="content">
    <div class="page-title">
        <h1>Consultancy Requests</h1>
    </div>

    <table class="consultancy-table">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Mobile Number</th>
                <th>Query</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>John Doe</td>
                <td>john@example.com</td>
                <td>+91-9876543210</td>
                <td>Looking for a cure for headaches using traditional plants.</td>
                <td class="status-pending">Pending</td>
                <td class="action-buttons">
                    <button class="resolve-btn">Resolve</button>
                    <button class="reject-btn">Reject</button>
                </td>
            </tr>
            <tr>
                <td>Jane Smith</td>
                <td>jane@example.com</td>
                <td>+91-9123456789</td>
                <td>Can you suggest plants for boosting immunity?</td>
                <td class="status-resolved">Resolved</td>
                <td class="action-buttons">
                    <button class="resolve-btn" disabled>Resolve</button>
                    <button class="reject-btn" disabled>Reject</button>
                </td>
            </tr>
            <tr>
                <td>Richard Roe</td>
                <td>richard@example.com</td>
                <td>+91-9876512345</td>
                <td>Are there any plants for skin care?</td>
                <td class="status-rejected">Rejected</td>
                <td class="action-buttons">
                    <button class="resolve-btn" disabled>Resolve</button>
                    <button class="reject-btn" disabled>Reject</button>
                </td>
            </tr>
            <!-- More rows can be added here -->
        </tbody>
    </table>
</div>



</body>
</html>
