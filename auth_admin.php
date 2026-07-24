<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check 1: Is a user logged in?
// Check 2: Is their role specifically 'admin'?
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // If not, send them away to the main login
    header("Location: login.php?error=unauthorized");
    exit();
}
?>