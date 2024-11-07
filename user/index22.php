<?php
include 'db_config.php';

// Function to search for diseases and medicines
function searchDiseaseAndMedicine($conn, $searchTerm) {
    $searchTerm = mysqli_real_escape_string($conn, $searchTerm);

    // Search for diseases
    $diseaseQuery = "SELECT * FROM disease WHERE disease_name LIKE '%$searchTerm%'";
    $diseaseResult = mysqli_query($conn, $diseaseQuery);
    $diseases = mysqli_fetch_all($diseaseResult, MYSQLI_ASSOC);

    // Search for medicines
    $medicineQuery = "SELECT m.*, d.disease_name 
                     FROM medicine m
                     JOIN disease d ON m.disease_id = d.disease_id
                     WHERE m.medicine_name LIKE '%$searchTerm%' OR d.disease_name LIKE '%$searchTerm%'";
    $medicineResult = mysqli_query($conn, $medicineQuery);
    $medicines = mysqli_fetch_all($medicineResult, MYSQLI_ASSOC);

    return array('diseases' => $diseases, 'medicines' => $medicines);
}

// Handle search form submission
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchResults = searchDiseaseAndMedicine($conn, $searchTerm);
    $diseases = $searchResults['diseases'];
    $medicines = $searchResults['medicines'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="shortcut icon" href="assets/favicon.png" type="png" />
  <title>RootRemedy - Home</title>

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
    <!-- Background Video -->
    <video autoplay loop muted class="bg-video">
      <source src="assets/bg_video.mp4" type="video/mp4" />
      Your browser does not support the video tag.
    </video>

    <!-- Centered Text -->
    <div class="centered-text">
      <h1>Empowering Health with Nature's Remedies</h1>
      <div class="search-bar">
        <form action="index.php" method="GET">
          <input type="text" placeholder="Search for plants, medicines and diseases..." name="search" class="search-input" />
          <button type="submit" class="search-button">Search</button>
        </form>
      </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg custom_nav-container">
      <!-- Navbar content -->
    </nav>
  </div>

  <!-- featured section -->
  <section class="featured_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Featured</h2>
      </div>
      <div class="row">
        <div class="col-sm-6 col-lg-4 mx-auto">
          <div class="box">
            <div class="img-box">
              <?php
              // Encode the image in base64 for display
              $plantImage = base64_encode($featuredPlant['image']);
              echo '<img src="data:image/jpeg;base64,' . $plantImage . '" alt="Featured Plant" />';
              ?>
            </div>
            <div class="detail-box">
              <h5><?php echo $featuredPlant['title']; ?></h5>
              <p><?php echo $featuredPlant['description']; ?></p>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 mx-auto">
          <div class="box">
            <div class="img-box">
              <?php
              // Encode the image in base64 for display
              $diseaseImage = base64_encode($featuredDisease['image']);
              echo '<img src="data:image/jpeg;base64,' . $diseaseImage . '" alt="Featured Disease" />';
              ?>
            </div>
            <div class="detail-box">
              <h5><?php echo $featuredDisease['title']; ?></h5>
              <p><?php echo $featuredDisease['description']; ?></p>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-4 mx-auto">
          <div class="box">
            <div class="img-box">
              <?php
              // Encode the image in base64 for display
              $medicineImage = base64_encode($featuredMedicine['image']);
              echo '<img src="data:image/jpeg;base64,' . $medicineImage . '" alt="Featured Medicine" />';
              ?>
            </div>
            <div class="detail-box">
              <h5><?php echo $featuredMedicine['title']; ?></h5>
              <p><?php echo $featuredMedicine['description']; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Search Results -->
  <?php if (isset($_GET['search']) && !empty($diseases) || !empty($medicines)): ?>
    <section class="search_results_section layout_padding">
      <div class="container">
        <div class="heading_container heading_center">
          <h2>Search Results</h2>
        </div>
        <div class="row">
          <?php if (!empty($diseases)): ?>
            <div class="col-md-6 col-lg-4 mx-auto">
              <h3>Diseases</h3>
              <?php foreach ($diseases as $disease): ?>
                <div class="box">
                  <div class="detail-box">
                    <h5><?php echo $disease['disease_name']; ?></h5>
                    <p><?php echo $disease['properties']; ?></p>
                    <p><strong>Symptoms:</strong> <?php echo $disease['symptoms']; ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($medicines)): ?>
            <div class="col-md-6 col-lg-4 mx-auto">
              <h3>Medicines</h3>
              <?php foreach ($medicines as $medicine): ?>
                <div class="box">
                  <div class="detail-box">
                    <h5><?php echo $medicine['medicine_name']; ?></h5>
                    <p><strong>For:</strong> <?php echo $medicine['disease_name']; ?></p>
                    <p><strong>Preparation Method:</strong> <?php echo $medicine['preparation_method']; ?></p>
                    <p><strong>How to Take:</strong> <?php echo $medicine['how_to_take']; ?></p>
                    <p><strong>Category:</strong> <?php echo $medicine['category']; ?></p>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <!-- contact section -->
  <section id="enquire" class="contact_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>Request Consultancy</h2>
      </div>
      <!-- Contact form content -->
    </div>
  </section>
  <!-- end contact section -->

  <!-- footer section -->
  <footer class="footer_section" style="">
    <!-- Footer content -->
  </footer>
  <!-- end footer section -->

  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>
</body>

</html>