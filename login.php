<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo "<script>window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Invalid credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | AudioStream</title>
    <style>
        body { background: #121212; color: white; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-card { background: #1e1e1e; padding: 40px; border-radius: 15px; width: 350px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid #333; }
        h2 { color: #f39c12; text-align: center; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #2a2a2a; border: 1px solid #444; border-radius: 8px; color: white; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #f39c12; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        .link { text-align: center; margin-top: 20px; font-size: 0.9rem; color: #888; }
        .link a { color: #f39c12; text-decoration: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Welcome Back</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <div class="link">
            New here? <a onclick="window.location.href='register.php'">Create an account</a>
        </div>
    </div>
</body>
</html>
