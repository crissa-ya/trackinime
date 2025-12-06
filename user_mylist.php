<?php
include "config.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];
$userId = $user['id'];

// Fetch user's personal list
$list = mysqli_query($conn, "
    SELECT anime.* 
    FROM anime 
    JOIN user_list ON anime.id = user_list.anime_id
    
    WHERE user_list.user_id = $userId
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My List - TrackiNime</title>
    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: url('assets/bg.gif') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }

        a { text-decoration: none; color: inherit; }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 220px;
            height: 100%;
            background: rgba(0,0,0,0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
        }

        .sidebar img.logo { width: 80px; margin-bottom: 20px; }
        .profile-pic { width: 100px; height: 100px; border-radius: 50%; overflow: hidden; margin-bottom: 10px; background: rgba(255,255,255,0.1); }
        .profile-pic img { width: 100%; height: 100%; object-fit: cover; }
        .username { font-weight: bold; margin-bottom: 10px; }
        .sidebar a.btn { display: block; width: 80%; padding: 10px; margin-bottom: 10px; text-align: center; background: rgba(120,105,255,0.8); border-radius: 8px; transition: 0.2s; }
        .sidebar a.btn:hover { background: rgba(110,95,245,1); }
        .sidebar a.btn.danger { background: rgba(255,75,75,0.8); }
        .sidebar a.btn.danger:hover { background: rgba(255,60,60,1); }

        .main-content { margin-left: 220px; padding: 20px 40px; }

        .header { text-align: left; margin-bottom: 20px; position: relative; }
        .header h1 { margin: 0; font-size: 26px; }
        .header .welcome { font-size: 20px; margin-top: 5px; }

        .back-btn {
            display: inline-block;
            padding: 8px 15px;
            margin-bottom: 20px;
            background: rgba(120,105,255,0.8);
            border-radius: 6px;
            color: white;
        }
        .back-btn:hover { background: rgba(110,95,245,1); }

        .anime-list { display: flex; flex-wrap: wrap; gap: 15px; }
        .anime-card {
            background: rgba(0,0,0,0.6); border-radius: 10px; overflow: hidden;
            display: flex; padding: 10px; align-items: flex-start; min-width: 300px; max-width: 320px;
        }
        .anime-card img { width: 120px; height: 160px; object-fit: cover; border-radius: 6px; flex-shrink: 0; margin-right: 15px; }
        .anime-info { flex: 1; }
        .anime-info p { margin: 3px 0; font-size: 14px; }
        .anime-actions { margin-top: 10px; }
        .anime-actions a { margin-right: 5px; padding: 5px 8px; border-radius: 5px; font-size: 12px; background: rgba(120,105,255,0.8); transition: 0.2s; }
        .anime-actions a.danger { background: rgba(255,75,75,0.8); }
        .anime-actions a:hover { opacity: 0.8; }
        .no-anime { text-align: left; margin-top: 40px; font-size: 18px; color: white; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="user_dashboard.php">
            <img src="assets/logo.png" class="logo">
        </a>
        
        <div class="profile-pic">
            <?php if(!empty($user['profile_image'])): ?>
                <img src="<?= $user['profile_image'] ?>">
            <?php endif; ?>
        </div>

        <div class="username"><?= htmlspecialchars($user['username']) ?></div>

        <a href="user_dashboard.php" class="btn">Dashboard</a>
        <a href="user_profile.php" class="btn">Profile</a>
        <a href="user_about.php" class="btn">About Us</a>
        <a href="logout.php" class="btn danger">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="header">
            <h1>My List</h1>
            <div class="welcome">Hello, <?= htmlspecialchars($user['fullname']) ?></div>
        </div>

        <!-- BACK BUTTON -->
        <a href="user_dashboard.php" class="back-btn">← Back</a>

        <?php if(mysqli_num_rows($list) === 0): ?>
            <div class="no-anime">Your list is empty.</div>
        <?php else: ?>
            <div class="anime-list">
                <?php while ($m = mysqli_fetch_assoc($list)): ?>
                <div class="anime-card">
                    <img src="<?= $m['cover_image'] ?>" alt="<?= htmlspecialchars($m['title']) ?>">
                    <div class="anime-info">
                        <p><strong>Title:</strong> <?= htmlspecialchars($m['anime_title']) ?></p>
                        <p><strong>Genre:</strong> <?= htmlspecialchars($m['genre']) ?></p>
                        <p><strong>Episodes:</strong> <?= htmlspecialchars($m['episodes']) ?></p>
                        <p><strong>Publish:</strong> <?= htmlspecialchars($m['publish_date'] ?? '-') ?></p>
                        <p><strong>Rating:</strong> <?= htmlspecialchars($m['rating']) ?></p>

                        <div class="anime-actions">
                            <a href="user_anime_view.php?id=<?= $m['id'] ?>&from=<?= urlencode($_SERVER['REQUEST_URI']) ?>">View</a>

                            <a href="user_remove.php?id=<?= $m['id'] ?>" class="danger">Remove</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
