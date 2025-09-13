<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=not_logged_in');
    exit();
}

if (!isset($_SESSION['login_time'])) {
    session_destroy();
    header('Location: login.php?error=session_expired');
    exit();
}

$session_timeout = isset($_SESSION['session_timeout']) ? $_SESSION['session_timeout'] : 300;

if (time() - $_SESSION['login_time'] > $session_timeout) {
    session_destroy();
    header('Location: login.php?error=session_expired&message=Your session has expired after 5 minutes. Please login again.');
    exit();
}

$_SESSION['login_time'] = time();

$remaining_time = $session_timeout - (time() - $_SESSION['login_time']);
$remaining_minutes = floor($remaining_time / 60);
$remaining_seconds = $remaining_time % 60;


?>