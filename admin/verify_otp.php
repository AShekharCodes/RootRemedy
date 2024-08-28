<?php
// Include the database configuration file
require_once 'db_config.php'; // Make sure this file exists and contains your database connection details

// Start session to handle OTP verification
session_start();

// Check if the email is set in the URL
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $otp = $_POST['otp'];

        // Retrieve OTP and expiry time from the database
        $stmt = $conn->prepare("SELECT otp, otp_expiry FROM admin WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $stored_otp = $row['otp'];
            $otp_expiry = $row['otp_expiry'];

            // Verify OTP and check if it is still valid
            if ($stored_otp == $otp && strtotime($otp_expiry) > time()) {
                // OTP is correct and not expired
                $_SESSION['email'] = $email; // Save email in session to allow password reset
                header("Location: reset_password.php"); // Redirect to password reset page
                exit();
            } else {
                echo "<script>alert('Invalid OTP or OTP has expired.');</script>";
            }
        } else {
            echo "<script>alert('Invalid request.');</script>";
        }
    }
} else {
    echo "<script>alert('Email is missing. Please try again.');</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/password-resets/password-reset-9/assets/css/password-reset-9.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        /* Footer styling */
        .footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            font-size: 14px;
            border-top: 1px solid #444;
        }

        .footer p {
            margin: 0;
        }

        .footer a {
            color: #ffc107;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .social-icons {
            margin-top: 10px;
        }

        .social-icon {
            color: #fff;
            margin: 0 10px;
            font-size: 20px;
            text-decoration: none;
        }

        .social-icon:hover {
            color: #ffc107;
        }

        /* Responsive design for footer */
        @media (max-width: 767px) {
            .footer .row {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <!-- OTP Verification Component -->
    <section class="bg-primary py-3 py-md-5 py-xl-8">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-12 col-md-6 col-xl-7">
                    <div class="d-flex justify-content-center text-bg-primary">
                        <div class="col-12 col-xl-9">
                            <img class="img-fluid rounded mb-4" loading="lazy" src="img/logo2.jfif" width="150" height="80" alt="BootstrapBrain Logo">
                            <hr class="border-primary-subtle mb-4">
                            <h2 class="h1 mb-4">Verify OTP</h2>
                            <p class="lead mb-5">Please enter the OTP sent to your email address.</p>
                            <div class="text-endx">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                    <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-5">
                    <div class="card border-0 rounded-4">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4">
                                        <h2 class="h3">Enter OTP</h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Please enter the OTP sent to your email to verify and reset your password.</h3>
                                    </div>
                                </div>
                            </div>
                            <form method="post" action="">
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" required>
                                            <label for="otp" class="form-label">OTP</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-lg" type="submit">Verify OTP</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end mt-4">
                                        <a href="forget_password.php" class="link-secondary text-decoration-none">Resend OTP</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; 2024 Root Remedy. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>Developers Contact | <a href="mailto:13purab@gmail.com">13purab@gmail.com</a> | <a href="tel:+911234567890">+91 1234567890</a></p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/purab.das.501/" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/in/purab-das-353761267/?originalSubdomain=in" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
