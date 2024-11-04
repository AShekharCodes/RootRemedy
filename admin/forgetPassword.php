<?php
// Include the database configuration file
require_once 'db_config.php'; // Make sure this file exists and contains your database connection details

// Function to generate a random OTP
function generateOTP($length = 6) {
    return str_pad(random_int(0, 999999), $length, '0', STR_PAD_LEFT);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate a unique OTP
            $otp = generateOTP();
            $expiry_time = date("Y-m-d H:i:s", strtotime('+10 minutes'));

            // Insert OTP into the database for verification
            $stmt = $conn->prepare("UPDATE admin SET otp = ?, otp_expiry = ? WHERE username = ?");
            $stmt->bind_param("sss", $otp, $expiry_time, $email);
            $stmt->execute();

            // Send the OTP to the user's email
            $subject = "Your OTP for Password Reset";
            $message = "Your OTP for password reset is: $otp\n\nThis OTP is valid for 10 minutes.";
            $headers = "From: Root-Remedy"; // Replace with your sender email address

            if (mail($email, $subject, $message, $headers)) {
                echo "<script>alert('An OTP has been sent to your email.');</script>";
                header("Location: verify_otp.php?email=$email");
                exit();
            } else {
                echo "<script>alert('Failed to send OTP email. Please try again later.');</script>";
            }
        } else {
            echo "<script>alert('Email address not found. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid email format.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
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
    <!-- Password Reset 9 - Bootstrap Brain Component -->
    <section class="bg-primary py-3 py-md-5 py-xl-8">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-12 col-md-6 col-xl-7">
                    <div class="d-flex justify-content-center text-bg-primary">
                        <div class="col-12 col-xl-9">
                            <img class="img-fluid rounded mb-4" loading="lazy" src="img/logo2.jfif" width="150" height="80" alt="BootstrapBrain Logo">
                            <hr class="border-primary-subtle mb-4">
                            <h2 class="h1 mb-4">Enter your username / email id associated.</h2>
                            <p class="lead mb-5">We will send you an OTP to reset your password.</p>
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
                                        <h2 class="h3">Password Reset</h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Provide the email address
                                            associated with your account to receive an OTP.</h3>
                                    </div>
                                </div>
                            </div>
                            <form method="post" action="">
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                                            <label for="email" class="form-label">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-lg" type="submit">Send OTP</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end mt-4">
                                        <a href="login.php" class="link-secondary text-decoration-none">Login</a>
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
                        <a href="https://www.facebook.com/purab.13" class="social-icon"><i class="fab fa-facebook"></i></a>
                        <a href="https://twitter.com/13_purab" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/in/purab-13/" class="social-icon"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
