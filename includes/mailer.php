<?php
// includes/mailer.php

function sendMail($to, $subject, $message) {
    // 1. Try to send real email (works if XAMPP is configured)
    $headers = "From: no-reply@jobportal.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $result = @mail($to, $subject, $message, $headers);

    // 2. ALWAYS Log to file (For Localhost Demos)
    // This ensures you can see the "Reset Link" even if real email fails
    $log_message = "-----------------------------------\n";
    $log_message .= "DATE: " . date('Y-m-d H:i:s') . "\n";
    $log_message .= "TO: $to\n";
    $log_message .= "SUBJECT: $subject\n";
    $log_message .= "MESSAGE:\n$message\n";
    $log_message .= "-----------------------------------\n\n";

    // Save to 'email_logs.txt' in the main folder
    file_put_contents(__DIR__ . '/../email_logs.txt', $log_message, FILE_APPEND);

    return true; // Always return true for demo purposes
}
?>