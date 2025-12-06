<?php
include "config.php";
session_start();

// Only admin access
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Handle profile image upload
    $profile_image_sql = "";
    if (!empty($_FILES['profile_image']['name'])) {
        $file = "uploads/" . time() . "-" . $_FILES['profile_image']['name'];
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $file);
        $profile_image_sql = ", profile_image=?";
    }

    // Prepare SQL query
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $password_sql = ", password=?";
    } else {
        $password_sql = "";
    }

    $sql = "UPDATE users SET fullname=?, username=?, email=? $profile_image_sql $password_sql WHERE id=?";
    $stmt = $conn->prepare($sql);

    // Bind parameters dynamically
    if (!empty($_FILES['profile_image']['name']) && !empty($password)) {
        $stmt->bind_param("ssssi", $fullname, $username, $email, $file, $password_hash, $user['id']); // Note: adjust types
    } elseif (!empty($_FILES['profile_image']['name'])) {
        $stmt->bind_param("sssi", $fullname, $username, $email, $file, $user['id']);
    } elseif (!empty($password)) {
        $stmt->bind_param("ssssi", $fullname, $username, $email, $password_hash, $user['id']);
    } else {
        $stmt->bind_param("sssi", $fullname, $username, $email, $user['id']);
    }

    $stmt->execute();
    $stmt->close();

    // Update session
    $_SESSION['user']['fullname'] = $fullname;
    $_SESSION['user']['username'] = $username;
    $_SESSION['user']['email'] = $email;
    if (!empty($_FILES['profile_image']['name'])) {
        $_SESSION['user']['profile_image'] = $file;
    }

    $success = "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile - TrackiNime</title>
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
            align-items: flex-start;
            min-height: 100vh;
        }

        .form-container {
            background: rgba(0,0,0,0.6);
            padding: 25px;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .form-container h2 { margin-bottom: 20px; }
        .form-container input {
            width: 95%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            margin-bottom: 15px;
            font-size: 15px;
        }
        .form-container input[type="file"] { padding: 5px; }
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
        .form-container a.back { display: inline-block; margin-top: 15px; color: rgba(120,105,255,0.8); }
        .form-container a.back:hover { color: rgba(110,95,245,1); }
        .success-msg { background: rgba(0,200,0,0.3); padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .profile-image-preview { width: 120px; height: 120px; border-radius: 50%; margin-bottom: 15px; object-fit: cover; }
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
                <img src="<?= htmlspecialchars($user['profile_image']) ?>">
            <?php endif; ?>
        </div>
        <div class="username"><?= htmlspecialchars($user['username']) ?></div>
        <a href="admin_dashboard.php" class="btn">Dashboard</a>
        <a href="admin_users.php" class="btn">Manage Users</a>
        <a href="logout.php" class="btn danger">Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="form-container">
            <h2>Admin Profile</h2>

            <?php if(!empty($success)): ?>
                <div class="success-msg"><?= $success ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <?php if(!empty($user['profile_image'])): ?>
                    <img src="<?= htmlspecialchars($user['profile_image']) ?>" class="profile-image-preview">
                <?php endif; ?>

                <input type="text" name="fullname" placeholder="Full Name" value="<?= htmlspecialchars($user['fullname']) ?>" required>
                <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($user['username']) ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>" required>
                <input type="password" name="password" placeholder="New Password (leave blank to keep current)">

                <input type="file" name="profile_image">

                <button type="submit">Update Profile</button>
            </form>

            <a href="admin_dashboard.php" class="back">← Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
