<?php
include 'db_config.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$data = [];
if ($category) {
    if ($category == "plants") {
        $sql = "SELECT plant_name, plant_properties, image1 FROM plant WHERE plant_name LIKE ? OR plant_properties LIKE ?";
    } elseif ($category == "medicines") {
        $sql = "SELECT medicine_name, preparation_method, how_to_take FROM medicine WHERE medicine_name LIKE ? OR preparation_method LIKE ?";
    } elseif ($category == "diseases") {
        $sql = "SELECT disease_name, properties, symptoms FROM disease WHERE disease_name LIKE ? OR properties LIKE ?";
    }

    $stmt = $conn->prepare($sql);
    $searchParam = '%' . $searchTerm . '%';
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" lang="en" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>RootRemedy - Search</title>
    <link href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet" />
    <link href="css/responsive.css" rel="stylesheet" />

    <style>
        h3{
            color: white;
        }
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            background-image: url('assets/featured_bg.jpg');
            background-size: cover;
            background-attachment: fixed;


        }
        .search-bg {
            position: relative;
            width: 100%;
            height: 300px;
        }
        .centered-text {
            text-align: center;
            padding-top: 100px;
            color: #fff;
            font-weight: 700;
        }
        .filter-controls {
            text-align: center;
            margin-top: 20px;
        }
        .filter-dropdown,
        .search-input,
        .apply-filter-button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin: 5px;
        }
        .apply-filter-button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .apply-filter-button:hover {
            background-color: #218838;
        }
        .container {
            max-width: 90%;
            margin: 30px auto;
        }
        .card {
    background-color: #f9f9f9; /* Light grey for a professional look */
    border-radius: 8px;
    margin: 20px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); /* Softer shadow for depth */
    transition: transform 0.3s, box-shadow 0.3s; /* Smooth transitions */
    overflow: hidden;
    text-align: center;
    min-height: 320px;
    padding: 20px;
    border: 1px solid #e0e0e0; /* Soft border for subtle detail */
}

.card:hover {
    transform: translateY(-5px); /* Slight lift on hover */
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
}

.card-title {
    font-size: 1.4em;
    color: #333333;
    margin: 10px 0;
    font-weight: 600;
}

.card-content {
    font-size: 1em;
    color: #666666;
    line-height: 1.6;
    padding: 10px 15px;
}

.card-footer {
    margin-top: 20px;
    padding: 15px;
    border-top: 1px solid #e0e0e0;
    color: #0073e6; /* Accent color */
    font-weight: 500;
    font-size: 0.9em;
    transition: color 0.3s;
}

.card-footer:hover {
    color: #005bb5; /* Darker shade for hover effect */
}

        .card img {
            max-height: 100%;
            width: 100%;
            object-fit: cover;
        }
        .card-body {
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Ensures equal space between elements */
}

       
        .card-text {
            font-size: 1rem;
            color: #333;
        }
        .search-input {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ddd;
    margin: 5px;
    width: 40%; /* Set the width to a larger percentage or fixed value */
}

    p.text-center{
        color: white;
    }
    
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg custom_nav-container">
      <a class="navbar-brand" href="index.php">
        <span>
          <img src="assets/logo.png" width="250px" />
        </span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class=""> </span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.php">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.html">Get in touch</a>
          </li>
        </ul>
      </div>
    </nav>
        <div class="search-bg">
        <div class="centered-text">
            <h1>Search for Your Desired Topic</h1>
        </div>
        <div class="filter-controls">
            <form id="filterForm" method="GET" action="">
                <select class="filter-dropdown" name="category" id="category">
                    <option value="">Select Category</option>
                    <option value="plants" <?= $category == 'plants' ? 'selected' : '' ?>>Plants</option>
                    <option value="medicines" <?= $category == 'medicines' ? 'selected' : '' ?>>Medicines</option>
                    <option value="diseases" <?= $category == 'diseases' ? 'selected' : '' ?>>Diseases</option>
                </select>
                <input type="text" name="search" class="search-input" placeholder="Enter search term..." value="<?= htmlspecialchars($searchTerm) ?>" />
                <button type="submit" class="apply-filter-button">Search</button>
            </form>
        </div>
    </div>

    <div class="container">
        <?php if (!empty($data)): ?>
            <h3>Results for <?= ucfirst($category) ?>: <?= htmlspecialchars($searchTerm) ?></h3>
            <div class="row">
                <?php foreach ($data as $item): ?>
                    <div class="card col-md-4">
                        <?php if ($category == "plants" && $item['image1']): ?>
                            <img src="data:image/jpeg;base64,<?= base64_encode($item['image1']) ?>" alt="<?= htmlspecialchars($item['plant_name']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['plant_name'] ?? $item['medicine_name'] ?? $item['disease_name']) ?></h5>
                            <p class="card-text">
                                <?= nl2br(htmlspecialchars($item['plant_properties'] ?? $item['preparation_method'] ?? $item['properties'])) ?>
                            </p>
                            <?php if (isset($item['how_to_take']) || isset($item['symptoms'])): ?>
                                <p class="card-text"><strong>Details:</strong> <?= htmlspecialchars($item['how_to_take'] ?? $item['symptoms']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No results found. Please try a different filter or search term.</p>
        <?php endif; ?>
    </div>
</body>
</html>
