<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$user, $email, $pass])) {
        echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error occurred.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join AudioStream</title>
    <style>
        body { background: #121212; color: white; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-card { background: #1e1e1e; padding: 40px; border-radius: 15px; width: 350px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid #333; }
        h2 { color: #f39c12; text-align: center; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin: 10px 0; background: #2a2a2a; border: 1px solid #444; border-radius: 8px; color: white; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #f39c12; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: #e67e22; }
        .link { text-align: center; margin-top: 20px; font-size: 0.9rem; color: #888; }
        .link a { color: #f39c12; text-decoration: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>Create Account</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Sign Up</button>
        </form>
        <div class="link">
            Already have an account? <a onclick="window.location.href='login.php'">Login here</a>
        </div>
    </div>
</body>
</html>
