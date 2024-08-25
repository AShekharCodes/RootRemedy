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
    <title>Root Remedy Admin - Delete Items</title>
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
        .selection-menu {
            text-align: center;
            margin-bottom: 30px;
        }
        .selection-menu select {
            padding: 10px;
            font-size: 1.2em;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .item-table th, .item-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        .item-table th {
            background-color: #3498db;
            color: white;
        }
        .item-table td {
            background-color: white;
            color: #34495e;
        }
        .delete-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #e74c3c;
            color: white;
            font-size: 1em;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 300px;
        }
        .modal-content h2 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #e74c3c;
        }
        .modal-buttons button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        .confirm-btn {
            background-color: #27ae60;
            color: white;
        }
        .cancel-btn {
            background-color: #95a5a6;
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
        <h1>Delete Plants, Medicines, or Diseases</h1>
    </div>

    <div class="selection-menu">
        <label for="item-select">Select Category:</label>
        <select id="item-select" onchange="updateTable()">
            <option value="plants">Plants</option>
            <option value="medicines">Medicines</option>
            <option value="diseases">Diseases</option>
        </select>
    </div>

    <table class="item-table" id="item-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description/Properties</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Neem</td>
                <td>Antibacterial, Antifungal</td>
                <td><button class="delete-btn" onclick="showModal()">Delete</button></td>
            </tr>
            <tr>
                <td>Tulsi</td>
                <td>Immunity Booster, Anti-inflammatory</td>
                <td><button class="delete-btn" onclick="showModal()">Delete</button></td>
            </tr>
            <!-- More rows will be dynamically added based on category -->
        </tbody>
    </table>
</div>

<!-- Confirmation Modal -->
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this item?</p>
        <div class="modal-buttons">
            <button class="confirm-btn" onclick="confirmDelete()">Yes</button>
            <button class="cancel-btn" onclick="hideModal()">No</button>
        </div>
    </div>
</div>

</body>
</html>