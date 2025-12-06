<?php
include "config.php";
session_start();

// Only admin access
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: admin_dashboard.php");
    exit;
}

// Fetch current anime data
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $desc = $_POST["description"];
    $genre = $_POST["genre"];
    $episodes = (int)$_POST["episodes"];
    $date = $_POST["release_date"];
    $rating = (float)$_POST["rating"];
    $section = $_POST["section"];

    $file = $anime['cover_image']; // Keep old cover by default
    if (!empty($_FILES["cover_image"]["name"])) {
        $file = "uploads/" . time() . "-" . basename($_FILES["cover_image"]["name"]);
        move_uploaded_file($_FILES["cover_image"]["tmp_name"], $file);
    }

    // Update anime
    $stmt = $conn->prepare("UPDATE anime SET title=?, description=?, genre=?, episodes=?, release_date=?, rating=?, cover_image=?, section=? WHERE id=?");
    $stmt->bind_param("sssissssi", 
        $title, $desc, $genre, $episodes, $date, $rating, $file, $section, $id
    );

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Anime - TrackiNime</title>
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
            padding: 20px 40px;
        }
        .header {
            text-align: left;
            margin-bottom: 30px;
            position: relative;
        }
        .header h1 { margin: 0; font-size: 26px; }
        .header .welcome { font-size: 20px; margin-top: 5px; }
        .header a.about {
            position: absolute;
            top: 20px;
            right: 40px;
            padding: 8px 15px;
            background: rgba(120,105,255,0.8);
            border-radius: 6px;
            color: white;
        }

        /* FORM */
        .form-container {
            background: rgba(0,0,0,0.6);
            padding: 25px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
        }
        .form-container h2 { margin-bottom: 20px; }
        .form-container label { display: block; margin: 10px 0 5px; }
        .form-container input,
        .form-container textarea {
            width: 95%;
            padding: 8px 10px;
            border-radius: 6px;
            border: none;
            margin-bottom: 10px;
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
        .cover-preview {
            margin: 10px 0;
        }
        .cover-preview img { width: 100px; border-radius: 6px; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="admin_dashboard.php">
            <img src="assets/logo.png" class="logo">
        </a>
        <div class="profile-pic">
            <?php if(!empty($user['profile_image'])): ?>
                <img src="<?= $user['profile_image'] ?>">
            <?php endif; ?>
        </div>
        <div class="username"><?= htmlspecialchars($user['username']) ?></div>
        <a href="admin_dashboard.php" class="btn">Dashboard</a>
        <a href="profile.php" class="btn">Profile</a>
        <a href="logout.php" class="btn danger">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="header">
            <h1>Edit Anime</h1>
            <div class="welcome">Hello, <?= htmlspecialchars($user['fullname']) ?></div>
            <a href="admin_about.php" class="about">About Us</a>
        </div>

        <div class="form-container">
            <h2>Anime Details</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <label>Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($anime['title']) ?>" required>

                <label>Description</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($anime['description']) ?></textarea>

                <label>Genre</label>
                <input type="text" name="genre" value="<?= htmlspecialchars($anime['genre']) ?>">

                <label>Episodes</label>
                <input type="number" name="episodes" value="<?= htmlspecialchars($anime['episodes']) ?>">

                <label>Release Date</label>
                <input type="date" name="release_date" value="<?= htmlspecialchars($anime['release_date']) ?>">

                <label>Rating</label>
                <input type="number" step="0.1" name="rating" value="<?= htmlspecialchars($anime['rating']) ?>">

                <label>Section</label>
                <select name="section" required>
                    <option value="top"  <?= $anime['section']=="top"?"selected":"" ?>>Top Anime</option>
                    <option value="most" <?= $anime['section']=="most"?"selected":"" ?>>Most Watched</option>
                    <option value="new"  <?= $anime['section']=="new"?"selected":"" ?>>New Release</option>
                </select>

                <div class="cover-preview">
                    <label>Current Cover</label>
                    <img src="<?= $anime['cover_image'] ?>" alt="Current Cover">
                </div>

                <label>Upload New Cover</label>
                <input type="file" name="cover_image">

                <button type="submit">Save Changes</button>
            </form>

            <a href="admin_dashboard.php" class="back">← Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
