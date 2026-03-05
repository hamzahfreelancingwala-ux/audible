<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])) { echo "<script>window.location.href='register.php';</script>"; exit; }
if(!isset($_GET['id'])) { header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM audiobooks WHERE id = ?");
$stmt->execute([$_GET['id']]);
$book = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pro Player | <?= htmlspecialchars($book['title']) ?></title>
    <style>
        :root { --accent: #f39c12; --bg: #0a0a0a; --card: #1a1a1a; }
        body { background: var(--bg); color: white; font-family: 'Segoe UI', sans-serif; height: 100vh; margin: 0; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .player-card { background: var(--card); padding: 30px; border-radius: 25px; width: 340px; text-align: center; border: 1px solid #333; box-shadow: 0 20px 40px rgba(0,0,0,0.6); position: relative; }
        .cover { width: 220px; height: 220px; border-radius: 15px; object-fit: cover; margin-bottom: 20px; border: 2px solid #333; }
        .prog-bg { width: 100%; height: 6px; background: #333; border-radius: 10px; margin: 25px 0; cursor: pointer; position: relative; }
        .prog-bar { height: 100%; background: var(--accent); width: 0%; border-radius: 10px; transition: width 0.2s linear; }
        .controls { display: flex; justify-content: space-around; align-items: center; }
        .play-btn { background: var(--accent); color: black; width: 60px; height: 60px; border-radius: 50%; font-size: 24px; cursor: pointer; border: none; transition: 0.2s; }
        .play-btn:hover { transform: scale(1.1); }
        .status { font-size: 11px; color: var(--accent); margin-bottom: 10px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }
        /* Hide the actual YouTube video frame */
        #yt-api-player { position: absolute; top: -5000px; left: -5000px; }
    </style>
</head>
<body>

    <div id="yt-api-player"></div>

    <div class="player-card">
        <div id="status" class="status">Loading Audio...</div>
        <img src="<?= htmlspecialchars($book['cover_image']) ?>" class="cover">
        <h3><?= htmlspecialchars($book['title']) ?></h3>
        
        <div class="prog-bg" id="seek">
            <div class="prog-bar" id="pbar"></div>
        </div>

        <div class="controls">
            <button onclick="skip(-15)" style="background:none; border:none; color:#888; cursor:pointer; font-size: 18px;">↺15</button>
            <button class="play-btn" id="playBtn" onclick="togglePlay()">▶</button>
            <button onclick="skip(15)" style="background:none; border:none; color:#888; cursor:pointer; font-size: 18px;">15↻</button>
        </div>
        
        <div style="margin-top: 25px;">
            <a href="index.php" style="color: #666; cursor: pointer; font-size: 13px; text-decoration: none; font-weight: bold;">BACK TO LIBRARY</a>
        </div>
    </div>

    <script src="https://www.youtube.com/iframe_api"></script>

    <script>
        let player;
        const playBtn = document.getElementById('playBtn');
        const status = document.getElementById('status');
        const videoId = '<?= $book['audio_url'] ?>'; 

        // 1. Setup the YouTube Player
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('yt-api-player', {
                height: '0',
                width: '0',
                videoId: videoId,
                playerVars: { 'autoplay': 0, 'controls': 0, 'disablekb': 1, 'rel': 0 },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange,
                    'onError': onPlayerError
                }
            });
        }

        function onPlayerReady(event) {
            status.innerText = "Ready to Stream";
            // Check progress every second
            setInterval(updateProgressBar, 1000);
        }

        // 2. Control Play/Pause
        function togglePlay() {
            const state = player.getPlayerState();
            if (state === YT.PlayerState.PLAYING) {
                player.pauseVideo();
            } else {
                player.playVideo();
                status.innerText = "Connecting...";
            }
        }

        // 3. Update UI based on YouTube's status
        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PLAYING) {
                playBtn.innerText = "⏸";
                status.innerText = "Now Playing";
            } else if (event.data == YT.PlayerState.PAUSED) {
                playBtn.innerText = "▶";
                status.innerText = "Paused";
            } else if (event.data == YT.PlayerState.BUFFERING) {
                status.innerText = "Buffering...";
            }
        }

        function updateProgressBar() {
            if (player && player.getDuration) {
                const duration = player.getDuration();
                const currentTime = player.getCurrentTime();
                if (duration > 0) {
                    const p = (currentTime / duration) * 100;
                    document.getElementById('pbar').style.width = p + "%";
                }
            }
        }

        function skip(s) {
            const time = player.getCurrentTime();
            player.seekTo(time + s, true);
        }

        function onPlayerError(e) {
            status.innerText = "YouTube Error";
            console.error("YT Error:", e);
        }

        // Seek functionality
        document.getElementById('seek').addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const pos = (e.pageX - rect.left) / this.offsetWidth;
            player.seekTo(pos * player.getDuration(), true);
        });
    </script>
</body>
</html>
