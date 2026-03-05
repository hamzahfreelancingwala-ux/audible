<?php
session_start();
include 'db.php';
$stmt = $pdo->query("SELECT * FROM audiobooks");
$books = $stmt->fetchAll();
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AudioStream | Home</title>
    <style>
        /* (Same CSS as previous index.php) */
        :root { --primary: #f39c12; --dark: #121212; --card: #1e1e1e; --text: #ffffff; }
        body { background: var(--dark); color: var(--text); font-family: 'Segoe UI', sans-serif; margin: 0; }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 15px 5%; background: rgba(0,0,0,0.9); border-bottom: 1px solid #333; position: sticky; top: 0; z-index: 1000; }
        .logo { font-size: 1.5rem; font-weight: bold; color: var(--primary); letter-spacing: 1px; }
        .hero { height: 350px; background: linear-gradient(45deg, rgba(0,0,0,0.8), rgba(243, 156, 18, 0.2)), url('https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?auto=format&fit=crop&q=80&w=1200'); background-size: cover; display: flex; align-items: center; padding-left: 5%; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 30px; padding: 40px 5%; }
        .book-card { background: var(--card); border-radius: 12px; overflow: hidden; transition: 0.3s; border: 1px solid #222; position: relative; }
        .book-card:hover { transform: scale(1.03); border-color: var(--primary); box-shadow: 0 10px 20px rgba(0,0,0,0.4); }
        .book-card img { width: 100%; height: 300px; object-fit: cover; }
        .book-info { padding: 15px; }
        .btn { background: var(--primary); color: #000; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <nav>
        <div class="logo">AUDIOSTREAM</div>
        <div>
            <?php if($isLoggedIn): ?>
                <span style="margin-right: 15px;">Hi, <?= $_SESSION['username'] ?></span>
                <a onclick="window.location.href='logout.php'" class="btn" style="background: #333; color: white;">Logout</a>
            <?php else: ?>
                <a onclick="window.location.href='login.php'" style="color: white; margin-right: 20px; cursor: pointer;">Login</a>
                <a onclick="window.location.href='register.php'" class="btn">Get Started</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="hero">
        <div>
            <h1 style="font-size: 3rem; margin: 0;">Unlimited Stories.</h1>
            <p style="font-size: 1.2rem; color: #ccc;">The best selection of audiobooks on the web.</p>
        </div>
    </div>

    <div class="grid">
        <?php foreach($books as $book): ?>
        <div class="book-card" onclick="playBook(<?= $book['id'] ?>)">
            <img src="<?= $book['cover_image'] ?>">
            <div class="book-info">
                <h3 style="margin: 0; font-size: 1.1rem;"><?= $book['title'] ?></h3>
                <p style="color: #777; font-size: 0.9rem; margin-top: 5px;"><?= $book['author'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <script>
        function playBook(id) {
            const loggedIn = <?= json_encode($isLoggedIn) ?>;
            if (loggedIn) {
                window.location.href = 'player.php?id=' + id;
            } else {
                alert('Please sign up to start listening!');
                window.location.href = 'register.php';
            }
        }
    </script>
</body>
</html>
