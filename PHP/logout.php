<?php
// logout.php - Script to manually log out users

// Start session
session_start();

// Check if user was logged in
$was_logged_in = isset($_SESSION['user_id']);

// Destroy all session data
session_destroy();

// Redirect to login page with logout message
if ($was_logged_in) {
    header('Location: login.php?message=You have been successfully logged out.');
} else {
    header('Location: login.php');
}
exit();
?>