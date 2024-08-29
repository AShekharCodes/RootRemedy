<?php
session_start(); // Start the session to use session variables
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Root Remedy - Consult Now</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <style>
    body {
      background-color: #f5f5f5;
    }

    .consult-header {
      padding-top: 100px;
      text-align: center;
    }

    .consult-form {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .contact-info a {
      color: #3498db;
    }

    .contact-info a:hover {
      text-decoration: underline;
    }

    footer {
      background-color: #2c3e50;
      color: white;
      padding: 15px 0;
    }
  </style>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Root Remedy</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Plants</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Diseases</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Medicines</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Consultation</a>
            </li>
          </ul>
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="#">Login</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <section class="consult-header">
    <div class="container">
      <h1 class="display-4 text-primary">Consult with Our Experts</h1>
    </div>
  </section>

  <div class="container mt-5">
    <div class="consult-form p-4 mb-5">
      <h2 class="text-center text-primary mb-4">Submit Your Query</h2>

      <!-- Display Bootstrap alerts for success or error messages -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?php echo $_SESSION['success']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?php echo $_SESSION['error']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <form action="submit_consult.php" method="POST">
        <div class="mb-3">
          <label for="name" class="form-label">Name:</label>
          <input type="text" id="name" name="name" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email:</label>
          <input type="email" id="email" name="email" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="mobile" class="form-label">Mobile Number:</label>
          <input type="tel" id="mobile" name="mobile" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="subject" class="form-label">Subject:</label>
          <input type="text" id="subject" name="subject" class="form-control" required />
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message/Query:</label>
          <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Submit Query</button>
        </div>
      </form>
    </div>
  </div>

  <div class="container text-center mb-5">
    <div class="contact-info">
      <p>For urgent queries, you can also reach us at:</p>
      <p>Phone: +91-1234567890</p>
      <p>
        Email: <a href="mailto:info@rootremedy.com">info@rootremedy.com</a>
      </p>
      <p>Follow us on social media for more updates:</p>
      <p>
        <a href="#">Facebook</a> | <a href="#">Twitter</a> |
        <a href="#">Instagram</a>
      </p>
    </div>
  </div>

  <footer class="text-center text-white">
    <p>&copy; 2024 Root Remedy | All Rights Reserved</p>
    <p>Contact: info@rootremedy.com | Phone: +91-1234567890</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>