<?php
// Start session
session_start();

// Database connection
include 'db_config.php';

// Initialize variables
$error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the email exists in the admin table
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session variables and redirect to dashboard
            $_SESSION['admin_logged_in'] = $email;
            header("Location: index.php"); // Redirect to dashboard page
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Remedy - Admin Login</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-9/assets/css/login-9.css">
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
    <section class="bg-primary py-3 py-md-5 py-xl-8">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-12 col-md-6 col-xl-7">
                    <div class="d-flex justify-content-center text-bg-primary">
                        <div class="col-12 col-xl-9">
                            <img class="img-fluid rounded mb-4" loading="lazy" src="img/logo2.jfif"
                                width="140" height="80" alt="Root remedy logo"> <p style="font-size: 24px; margin: 0;"><b>ROOT REMEDY</b></p>

                            <hr class="border-primary-subtle mb-4">
                            <h2 class="h1 mb-4" style="font-size: 18px;">Login to continue your journey with Root Remedy.</h2>
                            <p class="lead mb-5">Its a Hierarchical Knowledge based system for indigenous medicines of North-east India</p>
                            <div class="text-endx">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                    class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                                    <path
                                        d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
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
                                        <h3>Sign in</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Display error message if any -->
                            <?php if (!empty($error)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $error ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="email" class="form-control" name="email" id="email"
                                                placeholder="name@example.com" required>
                                            <label for="email" class="form-label">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="password" id="password"
                                                value="" placeholder="Password" required>
                                            <label for="password" class="form-label">Password</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" name="remember_me"
                                                id="remember_me">
                                            <label class="form-check-label text-secondary" for="remember_me">
                                                Keep me logged in
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary btn-lg" type="submit">Log in now</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="col-12">
                                    <div
                                        class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end mt-4">
                                        <a href="forgetPassword.php">Forgot password</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
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
                <p>Developers Contact | <a href="mailto:abc123@gmail.com">13purab@gmail.com</a> | <a href="tel:+911234567890">+91 1234567890</a></p>
                <!-- Add social media links or icons here if needed -->
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
