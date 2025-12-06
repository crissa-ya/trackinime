<?php
include "config.php";
session_start();

// Only admin access
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}


$user = $_SESSION["user"];

$res = mysqli_query($conn, "SELECT * FROM about LIMIT 1");
$about = mysqli_fetch_assoc($res);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST["content"];
    $stmt = $conn->prepare("UPDATE about SET content = ? WHERE id = 1");
    $stmt->bind_param("s", $content);
    $stmt->execute();
    $stmt->close();

    header("Location: user_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>About Us - TrackiNime</title>
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
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin-top: -70px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            width: 100%;
        }
        .header h1 { margin: 0; font-size: 26px; }

        /* FORM */
        .form-container {
            background: rgba(0,0,0,0.6);
            padding: 25px;
            border-radius: 10px;
            max-width: 600px;
            width: 100%;
        }
        .form-container h2 { margin-bottom: 20px; }
        .form-container textarea {
            width: 97%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            margin-bottom: 15px;
            font-size: 15px;
            resize: vertical;
            background: rgba(176, 176, 223, 0.5); /* semi-transparent dark blue */
            color: white;
        }
        .form-container button {
            padding: 10px 15px;
            background: rgba(120,105,255,0.8);
            border: none;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            transition: 0.2s;
        }
        .form-container button:hover { background: rgba(110,95,245,1); }

        .form-container a.back {
            display: inline-block;
            margin-top: 15px;
            color: rgba(120,105,255,0.8);
        }
        .form-container a.back:hover { color: rgba(110,95,245,1); }
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
                <img src="<?= htmlspecialchars($user['profile_image']) ?>">
            <?php endif; ?>
        </div>
        <div class="username"><?= htmlspecialchars($user['username']) ?></div>
        <a href="user_dashboard.php" class="btn">Dashboard</a>
        <a href="user_profile.php" class="btn">Profile</a>
        <a href="user_mylist.php" class="btn">My List</a>
        <a href="logout.php" class="btn danger">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="form-container">
            <h2>About Us</h2>
            <div class="about-view">
                <p style="white-space:pre-line; font-size:16px;">
                    <?= htmlspecialchars($about['content']) ?>
                </p>
            </div>

            <a href="user_dashboard.php" class="back">← Back to Dashboard</a>

        </div>
    </div>

</body>
</html>
