<?php include 'getfeatured.php'; ?>
<?php
$status = '';
$newsletter_status = '';
if (isset($_GET['status'])) {
  $status = $_GET['status'];
}
if (isset($_GET['newsletter_status'])) {
  $newsletter_status = $_GET['newsletter_status'];
}
include 'db_config.php';
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
        <input type="text" placeholder="Search for plants, medicines and diseases..." name="search" class="search-input" />
        <button type="submit" class="search-button">Search</button>
      </div>
    </div>

    <!-- Navbar -->
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
            <a class="nav-link" href="filteredsearch.php"> Filtered Search</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#enquire">Enquire</a>
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

  <!-- end featured section -->

  <!-- contact section -->
  <section id="enquire" class="contact_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>Enquire</h2>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="form_container contact-form">
            <?php if ($status == 'success'): ?>
              <div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
                Your message has been sent successfully!
              </div>
            <?php elseif ($status == 'error'): ?>
              <div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
                There was an error sending your message. Please try again.
              </div>
            <?php endif; ?>
            <form action="userconsult.php" method="POST">
              <div class="form-row">
                <div class="col-lg-6">
                  <div>
                    <input type="text" name="name" placeholder="Your Name" required />
                  </div>
                </div>
                <div class="col-lg-6">
                  <div>
                    <input type="tel" name="phone_number" placeholder="Phone Number" required />
                  </div>
                </div>
              </div>
              <div>
                <input type="email" name="email" placeholder="Email" required />
              </div>
              <div>
                <input type="text" name="subject" placeholder="Subject" required />
              </div>
              <div>
                <textarea name="message" placeholder="Message" required></textarea>
              </div>
              <div class="btn_box">
                <button type="submit">SEND</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- end contact section -->

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
        <div class="col-md-6 col-lg-3 footer_col">
          <h4>Newsletter Subscription</h4>
          <?php if ($newsletter_status == 'subscribed'): ?>
            <div id="alert" class="alert alert-success alert-dismissible fade show" role="alert">
              You have successfully subscribed to our newsletter!
            </div>
          <?php elseif ($newsletter_status == 'error'): ?>
            <div id="alert" class="alert alert-danger alert-dismissible fade show" role="alert">
              There was an error subscribing. Please try again.
            </div>
          <?php endif; ?>
          <form action="subscribe.php" method="POST">
            <input type="email" name="subscribers" placeholder="Enter email" required />
            <div class="btn_box">
              <button type="submit">Subscribe</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </footer>
  <!-- end footer section -->

  <!-- bootstrap js -->
  <script src="js/bootstrap.js"></script>
  <!-- custom js -->
  <script src="js/custom.js"></script>
</body>

</html>
