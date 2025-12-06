<?php
include "config.php";
session_start();

$user = $_SESSION["user"] ?? null;

// Fetch all anime
$anime = mysqli_query($conn, "SELECT * FROM anime");
?>

<!DOCTYPE html>
<html>
<head>
    <title>TrackiNime</title>
    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: url('assets/bg.gif') no-repeat center center fixed;
            background-size: cover;
            color: white;
        }
        a { text-decoration: none; color: inherit; }

        /* SIDEBAR */
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
        .username { font-weight: bold; margin-bottom: 20px; }
        .sidebar a.btn {
            display: block;
            width: 80%;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
            background: rgba(120,105,255,0.8);
            border-radius: 8px;
            transition: 0.2s;
        }
        .sidebar a.btn:hover { background: rgba(110,95,245,1); }
        .sidebar a.btn.danger { background: rgba(255,75,75,0.8); }
        .sidebar a.btn.danger:hover { background: rgba(255,60,60,1); }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 220px;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .main-content h1 {
            font-size: 36px;
            margin-bottom: 30px;
        }

        .anime-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            width: 100%;
        }

        .anime-card {
            background: rgba(0,0,0,0.6);
            border-radius: 10px;
            overflow: hidden;
            width: 200px;
            text-align: center;
            padding: 10px;
            transition: 0.2s;
        }
        .anime-card:hover { background: rgba(0,0,0,0.8); }
        .anime-card img.cover {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .anime-card h3 {
            margin: 0;
            font-size: 16px;
        }
        .anime-card h3 a { color: white; }
        .anime-card h3 a:hover { color: rgba(120,105,255,0.8); }

    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <img src="assets/logo.png" class="logo">
        <?php if($user): ?>
            <div class="profile-pic">
                <?php if(!empty($user['profile_image'])): ?>
                    <img src="<?= htmlspecialchars($user['profile_image']) ?>">
                <?php endif; ?>
            </div>
            <div class="username"><?= htmlspecialchars($user['username']) ?></div>
            <a href="dashboard.php" class="btn">Dashboard</a>
            <a href="profile.php" class="btn">Profile</a>
            <a href="logout.php" class="btn danger">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn">Register</a>
        <?php endif; ?>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <h1>TrackiNime</h1>

        <div class="anime-grid">
            <?php while($a = mysqli_fetch_assoc($anime)): ?>
                <div class="anime-card">
                    <img src="<?= htmlspecialchars($a['cover_image']) ?>" class="cover">
                    <h3><a href="anime_view.php?id=<?= $a['id'] ?>"><?= htmlspecialchars($a['title']) ?></a></h3>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>
