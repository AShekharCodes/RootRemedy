<?php include 'getfeatured.php'; ?>
<?php
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
        <!-- Background Video -->
        <video autoplay loop muted class="bg-video">
            <source src="assets/bg_video.mp4" type="video/mp4" />
            Your browser does not support the video tag.
        </video>

        <!-- Centered Text -->
        <div class="centered-text">
            <h1>Empowering Health with Nature's Remedies</h1>
            <div class="search-bar">
                <input type="text" placeholder="Search for plants, medicines and diseases..." name="search"
                    class="search-input" />
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

</body>

</html>