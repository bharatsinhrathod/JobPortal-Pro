<?php
// login.php
session_start();
require 'includes/db.php';
require 'includes/header.php';

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['full_name'];
        $_SESSION['profile_image'] = $user['profile_image'];

        // === REDIRECT LOGIC ===
        if ($user['role'] === 'admin') {
            echo "<script>window.location.href='admin_dashboard.php';</script>";
        } elseif ($user['role'] === 'recruiter') {
            echo "<script>window.location.href='dashboard.php';</script>";
        } else {
            // CHANGED: Applicants now go to Seeker Dashboard (No Search Bar)
            echo "<script>window.location.href='seeker_dashboard.php';</script>";
        }
        exit;
        // ======================
        
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="col-md-5 col-lg-4">
        
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
                
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dark">Welcome Back</h2>
                    <p class="text-muted">Please login to continue</p>
                </div>

                <?php if($error): ?>
                    <div class="alert alert-danger py-2 text-center rounded-3"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required>
                        <label for="floatingInput">Email address</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
                        <label for="floatingPassword">Password</label>
                        <div class="text-end mt-1">
                            <a href="forgot_password.php" class="text-decoration-none small">Forgot Password?</a>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm">Login</button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="mb-2 text-muted small">Don't have an account?</p>
                    <a href="register.php" class="btn btn-outline-primary w-100 rounded-pill fw-bold py-2 mb-3">
                        Create New Account
                    </a>
                    <a href="index.php" class="btn btn-light w-100 rounded-pill text-secondary fw-bold py-2 shadow-sm border">
                        <span class="me-2">&larr;</span> Back to Home
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>