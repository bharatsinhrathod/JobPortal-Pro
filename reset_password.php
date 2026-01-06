<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

$msg = "";
$error = "";

// 1. Get Token & Email from URL (GET) or Form (POST)
$token = $_GET['token'] ?? $_POST['token'] ?? '';
$email = $_GET['email'] ?? $_POST['email'] ?? '';

if (!$token || !$email) {
    die("<div class='container py-5 text-center'><h3>Invalid Request (Missing Token)</h3></div>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    if ($new_pass === $confirm_pass) {
        // 2. Verify Token using MySQL time
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND reset_token = ? AND reset_expires > NOW()");
        $stmt->execute([$email, $token]);
        $user = $stmt->fetch();

        if ($user) {
            $hash = password_hash($new_pass, PASSWORD_DEFAULT);
            // 3. Clear token after use
            $update = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE user_id = ?");
            $update->execute([$hash, $user['user_id']]);
            
            $msg = "Success! Password updated. <a href='login.php'>Login here</a>.";
        } else {
            $error = "Invalid or expired token. Please request a new link.";
        }
    } else {
        $error = "Passwords do not match.";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-center mb-4">Reset Password</h3>

                    <?php if($msg): ?>
                        <div class="alert alert-success text-center"><?php echo $msg; ?></div>
                    <?php else: ?>
                        <?php if($error): ?><div class="alert alert-danger text-center"><?php echo $error; ?></div><?php endif; ?>
                        
                        <form method="POST">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                            <div class="mb-3">
                                <label class="form-label fw-bold">New Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Update Password</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>