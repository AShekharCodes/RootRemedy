<?php
include 'getfeatured.php';
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

$status = '';
if (isset($_GET['status'])) {
    $status = $_GET['status'];
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" lang="en" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" href="assets/favicon.png" type="png" />
    <title>RootRemedy - Search</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet" />

    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
</head>

<body>
    <div class="nav_and_bg">
        <!-- Background Image -->
        <img src="assets/featured_bg.jpg" alt="Background image" class="search-bg">

        <!-- Centered Text -->
        <div class="centered-text">
            <h1>Search for your desired topic</h1>
            <div class="filter-search-bar">
                <form id="filterForm" method="GET">
                    <input type="text" placeholder="Search for plants, medicines and diseases..." name="search"
                        class="filter-search-input" value="<?= htmlspecialchars($searchTerm) ?>" />
                    <!-- Filter Dropdowns and Apply Filter Button -->
                    <div class="filter-controls">
                        <select class="filter-dropdown" name="category" id="category">
                            <option value="">Select Category</option>
                            <option value="plants" <?= $category == 'plants' ? 'selected' : '' ?>>Plants</option>
                            <option value="medicines" <?= $category == 'medicines' ? 'selected' : '' ?>>Medicines</option>
                            <option value="diseases" <?= $category == 'diseases' ? 'selected' : '' ?>>Diseases</option>
                        </select>
                    </div>
                    <button type="submit" class="filter-search-button">Search</button>
                </form>
            </div>
        </div>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg custom_nav-container">
            <a class="navbar-brand" href="index.php">
                <span>
                    <img src="assets/logo.png" width="250px" />
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="filteredsearch.php"> Filtered Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#enquire">Enquire</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Get in touch</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <div class="container-fluid mx-0 filter-results">
        <?php if (!empty($data)): ?>
            <h3 class="text-center mb-4">Results for <?= ucfirst($category) ?>: <?= htmlspecialchars($searchTerm) ?></h3>
            <div class="row gy-3 g-md-3">
                <?php foreach ($data as $item): ?>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <?php if ($category == "plants" && $item['image1']): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($item['image1']) ?>" class="card-img-top"
                                    alt="<?= htmlspecialchars($item['plant_name']) ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?= htmlspecialchars($item['plant_name'] ?? $item['medicine_name'] ?? $item['disease_name']) ?>
                                </h5>
                                <p class="card-text">
                                    <?= nl2br(htmlspecialchars($item['plant_properties'] ?? $item['preparation_method'] ?? $item['properties'])) ?>
                                </p>
                                <?php if (isset($item['how_to_take']) || isset($item['symptoms'])): ?>
                                    <p class="card-text"><strong>Details:</strong>
                                        <?= htmlspecialchars($item['how_to_take'] ?? $item['symptoms']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No results found. Please try a different filter or search term.</p>
        <?php endif; ?>
    </div>


    <footer class="footer_section">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-3 footer_col">
                    <div class="footer_contact">
                        <h4>Reach at..</h4>
                        <div class="contact_link_box">
                            <a href="https://maps.app.goo.gl/qrnwvZ2drezLP9MQA" target="_blank">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                <span> Click to get location</span>
                            </a>
                            <a href="">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span> +91 1234567890 </span>
                            </a>
                            <a href="">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span> rootremedy@gmail.com </span>
                            </a>
                        </div>
                    </div>
                    <div class="footer_social">
                        <a href="">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                        </a>
                        <a href="">
                            <i class="fa fa-instagram" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2 footer_col">
                    <div class="footer_link_box">
                        <h4>Links</h4>
                        <div class="footer_links">
                            <a class="active" href="index.php"> Home </a>
                            <a class="" href="about.php"> Filtered Search </a>
                            <a class="" href="index.php#enquire"> Enquire </a>
                            <a class="" href="about.php"> About Us </a>
                            <a class="" href="contact.php"> Get In Touch </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 footer_col">
                    <h4>Our Location</h4>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d872.9648967683238!2d91.6187429878337!3d26.129819089292443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x375a43f3fffffff9%3A0x122d2ba3a82829ab!2sAssam%20Don%20Bosco%20University%2C%20Azara%20Guwahati!5e1!3m2!1sen!2sin!4v1729932004231!5m2!1sen!2sin"
                        style="border: 0" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <div class="col-md-6 col-lg-3 footer_col">
                    <h4>Newsletter</h4>
                    <?php if ($status == 'success'): ?>
                        <div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                            Subscribed!
                        </div>
                    <?php elseif ($status == 'error'): ?>
                        <div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                            Unable to Subscribe.
                        </div>
                    <?php endif; ?>
                    <form action="subscribe.php" method="POST">
                        <input type="email" name="subscribers" placeholder="Enter email" required />
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
            <div class="footer-info">
                <p>
                    &copy; <span id="displayYear"></span> All Rights Reserved By Root
                    Remedy
                </p>
            </div>
        </div>
    </footer>
    <!-- jQuery -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <!-- bootstrap js -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- custom js -->
    <script type="text/javascript" src="js/custom.js"></script>

</body>

</html>