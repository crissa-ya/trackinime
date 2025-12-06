<?php
include "config.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us - TrackiNime</title>
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

        .sidebar img.logo {
            width: 80px;
            margin-bottom: 20px;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            overflow: hidden;
            margin-bottom: 10px;
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .username {
            font-weight: bold;
            margin-bottom: 10px;
        }

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

        .sidebar a.btn:hover {
            background: rgba(110,95,245,1);
        }

        .sidebar a.btn.danger {
            background: rgba(255,75,75,0.8);
        }

        .sidebar a.btn.danger:hover {
            background: rgba(255,60,60,1);
        }

        /* MAIN CONTENT CENTERED */
        .main-content {
            margin-left: 220px;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 200px;
        }

        .box {
            background: rgba(0,0,40,0.35);
            border-radius: 12px;
            padding: 25px;
            width: 100%;
            max-width: 600px;
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 20px;
        }

        ul {
            padding-left: 20px;
            line-height: 2;
        }

        ul li { font-size: 16px; }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 15px;
            background: rgba(120,105,255,0.8);
            border-radius: 6px;
        }

        .back-btn:hover {
            background: rgba(120,105,255,1);
        }
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
        <a href="user_mylist.php" class="btn">My List</a>
        <a href="user_about.php" class="btn">About Us</a>
        <a href="logout.php" class="btn danger">Logout</a>
    </div>


    <!-- MAIN CONTENT CENTERED -->
    <div class="main-content">
        <div class="box">
            <h1>Contact Us</h1>

            <ul>
                <li><strong>Facebook:</strong> TrackiNime</li>
                <li><strong>Instagram:</strong> @trackinime</li>
                <li><strong>YouTube:</strong> TrackiNime Channel</li>
                <li><strong>Email:</strong> trackinime@gmail.com</li>
            </ul>

            <a href="user_dashboard.php" class="back-btn">← Back</a>
        </div>
    </div>

</body>
</html>
