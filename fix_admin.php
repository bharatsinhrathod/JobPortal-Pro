<?php
// fix_admin.php
require 'includes/db.php';

$email = 'admin@jobportal.com';
$password = '123456';
$new_hash = password_hash($password, PASSWORD_DEFAULT);

// 1. Update the Admin Password
$sql = "UPDATE users SET password_hash = :hash WHERE email = :email";
$stmt = $pdo->prepare($sql);

if ($stmt->execute(['hash' => $new_hash, 'email' => $email])) {
    echo "<h1>✅ Admin Fixed!</h1>";
    echo "<p>Email: <strong>$email</strong></p>";
    echo "<p>Password: <strong>$password</strong></p>";
    echo "<p>Hash updated successfully.</p>";
    echo "<br><a href='login.php'>Go to Login Page</a>";
} else {
    echo "<h1>❌ Error</h1>";
    echo "Could not update admin user. Make sure the user 'admin@jobportal.com' exists in your database.";
}
?>