<?php

session_start();

$was_logged_in = isset($_SESSION['user_id']);

session_destroy();

if ($was_logged_in) {
    header('Location: login.php?message=You have been successfully logged out.');
} else {
    header('Location: login.php');
}
exit();
?>