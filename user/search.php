<?php
// Start by checking if the search term was provided
if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $searchQuery = trim($_GET['query']);

    include 'db_config.php';

    // Prepare the SQL query for disease based on the search term
    $diseaseStmt = $conn->prepare("SELECT * FROM disease WHERE disease_name LIKE ?");
    $searchTerm = "%" . $searchQuery . "%";
    $diseaseStmt->bind_param("s", $searchTerm);
    $diseaseStmt->execute();
    $diseaseResult = $diseaseStmt->get_result();
    $disease = $diseaseResult->fetch_assoc();

    // Initialize arrays to hold medicines and plants
    $medicines = [];
    $plants = [];

    // Query for Medicines associated with the disease if the disease exists
    if ($disease) {
        // Fetch medicines related to the disease along with plant names
        $medicineStmt = $conn->prepare("
            SELECT m.medicine_name, m.preparation_method, m.how_to_take, m.category, 
                   GROUP_CONCAT(p.plant_name SEPARATOR ', ') AS plant_names
            FROM medicine m
            LEFT JOIN combination_plants cp ON m.combination_id = cp.combination_id
            LEFT JOIN plant p ON cp.plant_id = p.plant_id
            WHERE m.disease_id = ?
            GROUP BY m.medicine_id
        ");
        $medicineStmt->bind_param("i", $disease['disease_id']);
        $medicineStmt->execute();
        $medicineResult = $medicineStmt->get_result();
        while ($row = $medicineResult->fetch_assoc()) {
            $medicines[] = $row;
        }

        // Query for Plants associated with the medicines and disease
        $plantStmt = $conn->prepare(
            "SELECT DISTINCT p.* FROM plant p
             JOIN combination_plants cp ON p.plant_id = cp.plant_id
             JOIN medicine m ON cp.combination_id = m.combination_id
             WHERE m.disease_id = ?"
        );
        $plantStmt->bind_param("i", $disease['disease_id']);
        $plantStmt->execute();
        $plantResult = $plantStmt->get_result();
        while ($row = $plantResult->fetch_assoc()) {
            $plants[] = $row;
        }
    }

    // Close statements if they are initialized
    if (isset($diseaseStmt)) {
        $diseaseStmt->close();
    }
    if (isset($medicineStmt)) {
        $medicineStmt->close();
    }
    if (isset($plantStmt)) {
        $plantStmt->close();
    }

    // Close the connection
    $conn->close();

} else {
    // If no search term, set an error message
    $errorMessage = "Please enter a search term.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" lang="en" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" href="assets/favicon.png" type="png" />
    <title>Rootremedy - Recommendations</title>

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
    <div class="nav_and_bg_search">
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

    <div class="result-section">
        <?php if (!empty($errorMessage)): ?>
            <p class="alert alert-danger"><?php echo $errorMessage; ?></p>
        <?php else: ?>
            <h2 class="result-caption">Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>

            <?php if (!empty($disease)): ?>
                <h3>Disease Details</h3>
                <div class="disease-card card mb-4 col-md-10 mx-auto">
                    <div class="card-body">
                        <h4><?php echo htmlspecialchars($disease['disease_name']); ?></h4>
                        <p><strong>Properties:</strong> <?php echo htmlspecialchars($disease['properties']); ?></p>
                        <p><strong>Symptoms:</strong> <?php echo htmlspecialchars($disease['symptoms']); ?></p>
                    </div>
                </div>

                <!-- Show Medicines for the Disease -->
                <?php if (!empty($medicines)): ?>
                    <div class="card-section mb-4">
                        <h3>Medicines</h3>
                        <?php foreach ($medicines as $medicine): ?>
                            <div class="medicine-card card mb-3 col-md-10 mx-auto">
                                <div class="card-body">
                                    <h4><?php echo htmlspecialchars($medicine['medicine_name']); ?></h4>
                                    <p><strong>Preparation Method:</strong>
                                        <?php echo htmlspecialchars($medicine['preparation_method']); ?></p>
                                    <p><strong>How to Take:</strong> <?php echo htmlspecialchars($medicine['how_to_take']); ?></p>
                                    <p><strong>Category:</strong> <?php echo htmlspecialchars($medicine['category']); ?></p>
                                    <p><strong>Plants Used:</strong> <?php echo htmlspecialchars($medicine['plant_names']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Show Plants used in the Medicines -->
                <?php if (!empty($plants)): ?>
                    <div class="card-section">
                        <h3>Plants</h3>
                        <div class="row">
                            <?php foreach ($plants as $plant): ?>
                                <div class="col-md-4 mb-4 mx-auto">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h4><?php echo htmlspecialchars($plant['plant_name']); ?></h4>
                                            <p><strong>Properties:</strong> <?php echo htmlspecialchars($plant['plant_properties']); ?>
                                            </p>
                                            <?php if (!empty($plant['image1'])): ?>
                                                <img src="data:image/jpeg;base64,<?php echo base64_encode($plant['image1']); ?>"
                                                    alt="Plant Image" class="img-fluid">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>


            <?php endif; ?>

            <!-- No Results Found -->
            <?php if (empty($disease) && empty($medicines) && empty($plants)): ?>
                <h2>No results found for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <!-- footer section -->
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
                            <a class="" href="contact.html"> Get In Touch </a>
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
                    <?php if (isset($_GET['status'])): ?>
                        <?php if ($_GET['status'] == 'subscribe_success'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Subscribed!

                            </div>
                        <?php elseif ($_GET['status'] == 'subscribe_error'): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Could not subscribe!

                            </div>
                        <?php endif; ?>
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
    <!-- footer section -->

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