<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';
require 'includes/mailer.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $subject = "New Contact Form Message from $name";
    $body = "<strong>Name:</strong> $name<br><strong>Email:</strong> $email<br><br><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message));

    if (sendMail("admin@jobportal.com", $subject, $body)) {
        $msg = "Message sent successfully!";
    } else {
        $msg = "Failed to send message.";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-5 bg-primary text-white p-5 d-flex flex-column justify-content-center">
                        <h3 class="fw-bold mb-4">Get in Touch</h3>
                        <p class="mb-4 opacity-75">Have questions? We'd love to hear from you.</p>
                        <div class="mb-3"><div class="fw-bold">📍 Address</div><small class="opacity-75">123 Tech Street, Silicon Valley</small></div>
                        <div class="mb-3"><div class="fw-bold">📞 Phone</div><small class="opacity-75">+1 234 567 890</small></div>
                        <div><div class="fw-bold">✉️ Email</div><small class="opacity-75">support@jobportal.com</small></div>
                    </div>

                    <div class="col-md-7 p-5 bg-white">
                        <h4 class="fw-bold mb-4 text-dark">Send a Message</h4>
                        <?php if($msg): ?><div class="alert alert-success rounded-pill small"><?php echo $msg; ?></div><?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Your Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Message</label>
                                <textarea name="message" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold w-100 mb-3">Send Message</button>
                            
                            <div class="text-center">
                                <a href="index.php" class="text-decoration-none text-muted fw-bold small">&larr; Back to Home</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(file_exists('includes/footer.php')) require 'includes/footer.php'; ?>