<?php if(session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>ACRS</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<header class="navbar">
    <h1>ANTI CORRUPTION REPORTING SYSTEM</h1>
    <nav>
        <a href="index.php">Home</a>

        <?php if(isset($_SESSION['user'])): ?>
            <?php 
                // 1. SAFELY FETCH ROLE AND EMAIL
                // Using ?? (null coalescing) to prevent "Undefined index" errors
                $user_role = $_SESSION['user']['role'] ?? 'user';
                $user_email = $_SESSION['user']['email'] ?? '';
                
                // 2. DEFINE ADMIN LIST (Fall back for your specific email)
                $is_admin = (strtolower($user_role) === 'admin' || $user_email === 'jhsowrov079@gmail.com');
            ?>

            <?php if($is_admin): ?>
                <a href="admin.php" style="color: #38bdf8; font-weight: bold;">Admin Panel</a>
            <?php else: ?>
                <a href="report.php">Report</a>
                <a href="my-complaints.php">My Complaints</a>
                <a href="track.php">Track</a>
            <?php endif; ?>
            
            <a href="logout.php" style="background: #ef4444; padding: 5px 10px; border-radius: 4px; color: white;">
                Logout (<?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>)
            </a>

        <?php else: ?>
            <a href="universal-login.php">Login</a>
            <a href="universal-signup.php">Signup</a>
            <a href="user-manual.php">User Manual</a>
        <?php endif; ?>
    </nav>
</header>