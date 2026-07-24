<?php
if(session_status() === PHP_SESSION_NONE) session_start();
include 'db.php';
include 'header.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); 
    $pass  = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user'] = $user; // Saves ID, Name, Email, and Role
        
        if (strtolower($user['role']) === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $msg = "Invalid email or password.";
    }
}
?>
<div class="center">
    <div class="card">
        <h2>Login</h2>
        <form method="POST">
            <input name="email" type="email" placeholder="Email" required>
            <input name="password" type="password" placeholder="Password" required>
            <button class="btn-primary" type="submit">Login</button>
        </form>
        <?php if($msg): ?>
            <p style="color: #ff4d4d; margin-top: 10px;"><?= $msg ?></p>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>