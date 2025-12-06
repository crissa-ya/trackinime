<?php
include "config.php";
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];

// Fetch by section
$topAnime = mysqli_query($conn, "SELECT * FROM anime WHERE section='top' ORDER BY id ASC");
$mostWatched = mysqli_query($conn, "SELECT * FROM anime WHERE section='most' ORDER BY watch_count ASC");
$newReleases = mysqli_query($conn, "SELECT * FROM anime WHERE section='new' ORDER BY release_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - TrackiNime</title>
    <style>
        body{margin:0;font-family:"Poppins",sans-serif;background:url('assets/bg.gif') no-repeat center center fixed;background-size:cover;color:white;}
        a{text-decoration:none;color:inherit;}
        .sidebar{position:fixed;left:0;top:0;width:220px;height:100%;background:rgba(0,0,0,0.85);display:flex;flex-direction:column;align-items:center;padding-top:20px;}
        .sidebar img.logo{width:80px;margin-bottom:20px;}
        .profile-pic {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            margin-bottom: 10px;
            cursor: pointer;
            background: rgba(255,255,255,0.1);
            transition: transform 0.2s;
        }
        .profile-pic:hover {
            transform: scale(1.05); /* Slight zoom effect */
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.3s;
        }

        .camera-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: 0.3s;
        }

        .profile-pic:hover .camera-overlay {
            opacity: 1;
        }

        .camera-overlay svg {
            width: 28px;
            height: 28px;
            fill: white;
        }

        
        .username{font-weight:bold;margin-bottom:20px;}
        .sidebar a.btn{display:block;width:80%;padding:10px;margin-bottom:10px;text-align:center;background: rgba(120,105,255,0.8);border-radius:8px;transition:0.2s;}
        .sidebar a.btn:hover{background: rgba(110,95,245,1);}
        .sidebar a.btn.danger{background: rgba(255,75,75,0.8);}
        .sidebar a.btn.danger:hover{background: rgba(255,60,60,1);}
        .main-content{margin-left:220px;padding:20px 40px;}
        .header{margin-bottom:20px;}
        .header h1{margin:0;font-size:26px;}
        .header .welcome{font-size:20px;margin-top:5px;}
        .section{margin-top:30px;}
        .header a.about {
            position: absolute;
            top: 20px;
            right: 40px;
            padding: 8px 15px;
            background: rgba(120,105,255,0.8);
            border-radius: 6px;
            color: white;
            text-decoration: none;
            transition: 0.2s;
        }

        .header a.about:hover {
            background: rgba(110,95,245,1);
        }

        .section h2{margin-bottom:15px;}
        .anime-list{display:flex;flex-wrap:wrap;gap:15px;}
        .anime-card{background:rgba(0,0,0,0.6);border-radius:10px;overflow:hidden;display:flex;padding:10px;align-items:flex-start;min-width:300px;max-width:320px;}
        .anime-card img{width:120px;height:160px;object-fit:cover;border-radius:6px;flex-shrink:0;margin-right:15px;}
        .anime-info{flex:1;}
        .anime-info p{margin:3px 0;font-size:14px;}
        .anime-actions{margin-top:10px;}
        .anime-actions a{margin-right:5px;padding:5px 8px;border-radius:5px;font-size:12px;background: rgba(120,105,255,0.8);transition:0.2s;}
        .anime-actions a.danger{background: rgba(255,75,75,0.8);}
        .anime-actions a:hover{opacity:0.8;}
        .add-anime{display:inline-block;margin-top:15px;padding:10px 15px;background: rgba(120,105,255,0.8);border-radius:6px;}
        .add-anime:hover{background: rgba(110,95,245,1);}
        .no-anime{margin-top:10px;font-size:16px;color:white;}
    </style>
</head>
<body>

<div class="sidebar">
    <a href="admin_dashboard.php"><img src="assets/logo.png" class="logo"></a>
    <!-- Sidebar Profile -->
    <div class="profile-pic" id="profilePic">
        <img src="<?= !empty($user['profile_image']) ? $user['profile_image'] : 'assets/default-avatar.png' ?>" alt="Profile Picture">
        <div class="camera-overlay">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 5a7 7 0 1 0 0 14 7 7 0 0 0 0-14zm9-2h-3.17l-1.84-2H7.01L5.17 3H2v2h20V3z"/>
            </svg>
        </div>
    </div>


    <!-- Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img src="<?= !empty($user['profile_image']) ? $user['profile_image'] : 'assets/default-avatar.png' ?>" alt="Profile Preview" class="modal-img">
            <div class="modal-actions">
                <button id="changeBtn">Change Profile</button>
                <button id="backBtn">Back</button>
            </div>
            <form id="uploadForm" action="admin_profile.php" method="POST" enctype="multipart/form-data" style="display:none;">
                <input type="file" name="profile_image" id="profileInput">
            </form>
        </div>
    </div>

    <style>
    /* Modal Styles */
    .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); justify-content:center; align-items:center; }
    .modal-content { background:#111; padding:20px; border-radius:10px; text-align:center; position:relative; }
    .modal-content img.modal-img { width:150px; height:150px; border-radius:50%; object-fit:cover; margin-bottom:15px; }
    .modal-actions button { margin:5px; padding:8px 15px; border:none; border-radius:5px; cursor:pointer; background: #765fff; color:white; transition:0.2s; }
    .modal-actions button:hover { background:#6e5ff5; }
    .close { position:absolute; top:10px; right:15px; font-size:24px; cursor:pointer; }
    </style>

    <script>
    const profilePic = document.getElementById('profilePic');
    const modal = document.getElementById('profileModal');
    const close = modal.querySelector('.close');
    const changeBtn = document.getElementById('changeBtn');
    const backBtn = document.getElementById('backBtn');
    const profileInput = document.getElementById('profileInput');
    const uploadForm = document.getElementById('uploadForm');

    // Open modal on click
    profilePic.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    // Close modal
    close.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    backBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Trigger file input when Change Profile clicked
    changeBtn.addEventListener('click', () => {
        profileInput.click();
    });

    // Submit form after file selected
    profileInput.addEventListener('change', () => {
        uploadForm.submit();
    });
    </script>


    <div class="username"><?= htmlspecialchars($user['username']) ?></div>
    <a href="admin_dashboard.php" class="btn">Dashboard</a>
    <a href="admin_profile.php" class="btn">Profile</a>

    <a href="admin_users.php" class="btn">Manage Users</a>

    <a href="logout.php" class="btn danger">Logout</a>
</div>

<div class="main-content">
    <div class="header">
        <h1>Admin Dashboard</h1>
        <div class="welcome">Hello, <?= htmlspecialchars($user['fullname']) ?></div>
        <a href="admin_about.php" class="about">About Us</a>
    </div>


    <a href="admin_add_anime.php" class="add-anime">+ Add New Anime</a>

    <!-- Top Anime -->
    <div class="section">
        <h2>Top Anime</h2>
        <?php if(mysqli_num_rows($topAnime) === 0): ?>
            <div class="no-anime">No top anime added yet.</div>
        <?php else: ?>
        <div class="anime-list">
            <?php while($a=mysqli_fetch_assoc($topAnime)): ?>
            <div class="anime-card">
                <img src="<?= $a['cover_image'] ?>" alt="<?= htmlspecialchars($a['title']) ?>">
                <div class="anime-info">
                    <p><strong>Title:</strong> <?= htmlspecialchars($a['title']) ?></p>
                    <p><strong>Genre:</strong> <?= htmlspecialchars($a['genre']) ?></p>
                    <p><strong>Episodes:</strong> <?= htmlspecialchars($a['episodes']) ?></p>
                    <p><strong>Rating:</strong> <?= htmlspecialchars($a['rating']) ?></p>
                    <div class="anime-actions">
                        <a href="admin_anime_view.php?id=<?= $a['id'] ?>">View</a>
                        <a href="admin_edit_anime.php?id=<?= $a['id'] ?>">Edit</a>
                        <a href="admin_delete_anime.php?id=<?= $a['id'] ?>" class="danger">Delete</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Most Watched -->
    <div class="section">
        <h2>Most Watched</h2>
        <?php if(mysqli_num_rows($mostWatched) === 0): ?>
            <div class="no-anime">No most watched anime yet.</div>
        <?php else: ?>
        <div class="anime-list">
            <?php while($a=mysqli_fetch_assoc($mostWatched)): ?>
            <div class="anime-card">
                <img src="<?= $a['cover_image'] ?>" alt="<?= htmlspecialchars($a['title']) ?>">
                <div class="anime-info">
                    <p><strong>Title:</strong> <?= htmlspecialchars($a['title']) ?></p>
                    <p><strong>Genre:</strong> <?= htmlspecialchars($a['genre']) ?></p>
                    <p><strong>Episodes:</strong> <?= htmlspecialchars($a['episodes']) ?></p>
                    <p><strong>Rating:</strong> <?= htmlspecialchars($a['rating']) ?></p>
                    <div class="anime-actions">
                        <a href="admin_anime_view.php?id=<?= $a['id'] ?>">View</a>
                        <a href="admin_edit_anime.php?id=<?= $a['id'] ?>">Edit</a>
                        <a href="admin_delete_anime.php?id=<?= $a['id'] ?>" class="danger">Delete</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- New Releases -->
    <div class="section">
        <h2>New Releases</h2>
        <?php if(mysqli_num_rows($newReleases) === 0): ?>
            <div class="no-anime">No new releases yet.</div>
        <?php else: ?>
        <div class="anime-list">
            <?php while($a=mysqli_fetch_assoc($newReleases)): ?>
            <div class="anime-card">
                <img src="<?= $a['cover_image'] ?>" alt="<?= htmlspecialchars($a['title']) ?>">
                <div class="anime-info">
                    <p><strong>Title:</strong> <?= htmlspecialchars($a['title']) ?></p>
                    <p><strong>Genre:</strong> <?= htmlspecialchars($a['genre']) ?></p>
                    <p><strong>Episodes:</strong> <?= htmlspecialchars($a['episodes']) ?></p>
                    <p><strong>Rating:</strong> <?= htmlspecialchars($a['rating']) ?></p>
                    <div class="anime-actions">
                        <a href="admin_anime_view.php?id=<?= $a['id'] ?>">View</a>
                        <a href="admin_edit_anime.php?id=<?= $a['id'] ?>">Edit</a>
                        <a href="admin_delete_anime.php?id=<?= $a['id'] ?>" class="danger">Delete</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
