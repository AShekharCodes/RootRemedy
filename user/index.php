<?php
session_start();
include 'getfeatured.php';
include 'db_config.php'
  ?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" lang="en" />
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
      <form class="search-form" action="search.php" method="GET">
        <input type="text" placeholder="Enter the disease you want remedies for..." name="query" class="search-input"
          required />
        <button type="submit" class="search-button">Search</button>
      </form>
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
              <p>
                <?php echo $featuredPlant['description']; ?>
              </p>
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
              <p>
                <?php echo $featuredDisease['description']; ?>
              </p>
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
              <p>
                <?php echo $featuredMedicine['description']; ?>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end featured section -->

  <!-- enquire section -->
  <section id="enquire" class="contact_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>Request Consultancy</h2>
      </div>
      <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] == 'userconsult_success'): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            Your enquiry has been submitted successfully!

          </div>
        <?php elseif ($_GET['status'] == 'userconsult_error'): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            There was an error submitting your enquiry. Please try again later.

          </div>
        <?php endif; ?>
      <?php endif; ?>


      <div class="row">
        <div class="col-md-12">
          <div class="form_container contact-form">
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
                <input name="message" placeholder="Message" required></input>
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
            style="border: 0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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