<?php
// session_check.php - Include this file in protected pages to check session timeout

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // User not logged in, redirect to login page
    header('Location: login.php?error=not_logged_in');
    exit();
}

// Check if login_time is set
if (!isset($_SESSION['login_time'])) {
    // Session corrupted, destroy and redirect to login
    session_destroy();
    header('Location: login.php?error=session_expired');
    exit();
}

// Get session timeout (default 5 minutes if not set)
$session_timeout = isset($_SESSION['session_timeout']) ? $_SESSION['session_timeout'] : 300;

// Check if session has expired (5 minutes = 300 seconds)
if (time() - $_SESSION['login_time'] > $session_timeout) {
    // Session expired, destroy session and redirect to login
    session_destroy();
    header('Location: login.php?error=session_expired&message=Your session has expired after 5 minutes. Please login again.');
    exit();
}

// Session is still valid, update login time to current time (extends session)
$_SESSION['login_time'] = time();

// Optional: Show remaining session time
$remaining_time = $session_timeout - (time() - $_SESSION['login_time']);
$remaining_minutes = floor($remaining_time / 60);
$remaining_seconds = $remaining_time % 60;

// You can use this in your pages to show remaining time
// echo "<div style='color: #666; font-size: 12px;'>Session expires in: {$remaining_minutes}m {$remaining_seconds}s</div>";
?>