<?php
include "config.php";
session_start();

$user = $_SESSION["user"] ?? null;

$id = $_GET["id"] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Fetch anime details safely
$stmt = $conn->prepare("SELECT * FROM anime WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$anime = $result->fetch_assoc();
$stmt->close();

if (!$anime) {
    echo "Anime not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($anime['title']) ?> - TrackiNime</title>
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
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 20px;
            min-height: 100vh;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            width: 100%;
        }
        .header h1 { margin: 0; font-size: 26px; }
        .header .welcome { font-size: 20px; margin-top: 5px; }
        .header a.about {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: rgba(120,105,255,0.8);
            border-radius: 6px;
            color: white;
        }

        .anime-container {
            background: rgba(0,0,0,0.6);
            padding: 25px;
            border-radius: 10px;
            max-width: 700px;
            text-align: center;
        }
        .anime-container img.cover {
            width: 250px;
            height: 350px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .anime-container h1 { margin-top: 0; font-size: 28px; }
        .anime-container p {
            margin: 12px 0;
            font-size: 16px;
            line-height: 1.6;
            word-wrap: break-word;
            text-align: justify;
        }

        .anime-container a.back {
            display: inline-block;
            margin-top: 20px;
            color: rgba(120,105,255,0.8);
        }
        .anime-container a.back:hover { color: rgba(110,95,245,1); }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="admin_dashboard.php">
            <img src="assets/logo.png" class="logo">
        </a>
        <?php if($user): ?>
            <div class="profile-pic">
                <?php if(!empty($user['profile_image'])): ?>
                    <img src="<?= htmlspecialchars($user['profile_image']) ?>">
                <?php endif; ?>
            </div>
            <div class="username"><?= htmlspecialchars($user['username']) ?></div>
            <a href="admin_dashboard.php" class="btn">Dashboard</a>
            <a href="admin_profile.php" class="btn">Profile</a>
            <a href="logout.php" class="btn danger">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn">Register</a>
        <?php endif; ?>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="anime-container">
            <img src="<?= htmlspecialchars($anime['cover_image']) ?>" alt="<?= htmlspecialchars($anime['title']) ?>" class="cover">
            <h1><?= htmlspecialchars($anime['title']) ?></h1>
            <p><b>Genre:</b> <?= htmlspecialchars($anime['genre']) ?></p>
            <p><b>Episodes:</b> <?= htmlspecialchars($anime['episodes']) ?></p>
            <p><b>Rating:</b> <?= htmlspecialchars($anime['rating']) ?></p>
            <p><b>Release Date:</b> <?= htmlspecialchars($anime['release_date'] ?? '-') ?></p>
            <p><?= nl2br(htmlspecialchars($anime['description'])) ?></p>

            <a href="admin_dashboard.php" class="back">← Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
