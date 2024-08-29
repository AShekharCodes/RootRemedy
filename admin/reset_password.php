<?php
// Include the database configuration file
require_once 'db_config.php'; // Make sure this file exists and contains your database connection details

// Start session to handle OTP verification
session_start();

// Check if the email is set in the session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password == $confirm_password) {
            // Update password in the database
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT); // Hash the new password
            $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                echo "<script>alert('Password reset successfully.'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error resetting password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match.');</script>";
        }
    }
} else {
    echo "<script>alert('Unauthorized access. Please verify OTP first.'); window.location.href='forget_password.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
    <!-- Password Reset Component -->
    <section class="bg-primary py-3 py-md-5 py-xl-8">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-12 col-md-6 col-xl-7">
                    <div class="d-flex justify-content-center text-bg-primary">
                        <div class="col-12 col-xl-9">
                            <img class="img-fluid rounded mb-4" loading="lazy" src="img/logo2.jfif" width="150" height="80" alt="BootstrapBrain Logo">
                            <hr class="border-primary-subtle mb-4">
                            <h2 class="h1 mb-4">Reset Password</h2>
                            <p class="lead mb-5">Please enter your new password below.</p>
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
                                        <h2 class="h3">Enter New Password</h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Please enter your new password to reset.</h3>
                                    </div>
                                </div>
                            </div>
                            <form method="post" action="">
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter New Password" required>
                                            <label for="new_password" class="form-label">New Password</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm New Password" required>
                                            <label for="confirm_password" class="form-label">Confirm Password</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-lg" type="submit">Reset Password</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
