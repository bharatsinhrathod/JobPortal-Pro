<?php
session_start();

// 1. Unset all session variables
$_SESSION = array();

// 2. Destroy the session cookie (if it exists)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the session
session_destroy();

// 4. Redirect to HOME PAGE (index.php)
header("Location: index.php");
exit;
?>