<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';
require 'includes/mailer.php';

$msg = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));

        // FIX: Use MySQL's own time (NOW) to prevent timezone mismatches
        $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $update->execute([$token, $email]);

        // FIX: URL Encode the email to handle special characters correctly
        $encoded_email = urlencode($email);
        $link = "http://localhost/job_portal/reset_password.php?token=$token&email=$encoded_email";
        
        $subject = "Password Reset Request";
        $message = "Click to reset: <a href='$link'>$link</a>";

        sendMail($email, $subject, $message);
        
        $msg = "Reset link sent! Check email_logs.txt";
    } else {
        $error = "Email not found!";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold text-center mb-4">Forgot Password?</h3>
                    <?php if($msg): ?><div class="alert alert-success text-center"><?php echo $msg; ?></div><?php endif; ?>
                    <?php if($error): ?><div class="alert alert-danger text-center"><?php echo $error; ?></div><?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Send Link</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>