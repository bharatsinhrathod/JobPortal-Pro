<?php
// reset_pass.php
require 'includes/db.php';

// 1. The password we want to use
$new_password = '123456';

// 2. Generate a secure hash using YOUR server's algorithm
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

// 3. Update ALL users to have this password
$sql = "UPDATE users SET password_hash = :hash";
$stmt = $pdo->prepare($sql);

if($stmt->execute(['hash' => $new_hash])) {
    echo "<h1>✅ Success!</h1>";
    echo "<p>All users passwords have been reset to: <strong>123456</strong></p>";
    echo "<p>The new hash generated is: " . htmlspecialchars($new_hash) . "</p>";
    echo "<br><a href='login.php'>Go to Login Page</a>";
} else {
    echo "<h1>❌ Error</h1>";
    echo "Could not update passwords.";
}
?>