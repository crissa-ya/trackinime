<?php
include "config.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION["user"];
$userId = $user["id"];

// FETCH UPDATED USER DATA
$res = mysqli_query($conn, "SELECT * FROM users WHERE id=$userId");
$data = mysqli_fetch_assoc($res);

$success = "";
$error = "";

// HANDLE UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $birthdate = $_POST["birthdate"];
    $username = $_POST["username"];

    $newPfp = $data["profile_image"];

    // IMAGE UPLOAD
    if (!empty($_FILES["profile_image"]["name"])) {
        $target = "uploads/" . basename($_FILES["profile_image"]["name"]);
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target);
        $newPfp = $target;
    }

    // PASSWORD (only update if filled)
    if (!empty($_POST["password"])) {
        $pass = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $pQuery = ", password='$pass'";
    } else {
        $pQuery = "";
    }

    $sql = "UPDATE users SET 
                fullname='$fullname',
                email='$email',
                address='$address',
                birthdate='$birthdate',
                username='$username',
                profile_image='$newPfp'
                $pQuery
            WHERE id=$userId";

    if (mysqli_query($conn, $sql)) {

        // Refresh session
        $_SESSION["user"] = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$userId"));

        $success = "Profile updated successfully!";
         // Redirect to dashboard
        header("Location: user_dashboard.php");
        exit;
    } else {
        $error = "Error updating profile!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>User Profile - TrackiNime</title>

<style>
body {
    margin: 0;
    font-family: "Poppins", sans-serif;
    background: url('assets/bg.gif') no-repeat center center fixed;
    background-size: cover;
    color: white;
}

/* SIDEBAR */
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 220px;
    height: 100%;
    background: rgba(0,0,0,0.85);
    padding-top: 20px;
    text-align: center;
}

.sidebar img.logo {
    width: 80px;
    margin-bottom: 20px;
}

.profile-pic {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    overflow: hidden;
    background: rgba(255,255,255,0.1);
    margin: auto;
}

.profile-pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.username {
    margin-top: 10px;
    font-weight: bold;
    margin-bottom: 20px;
}

.sidebar a.btn {
    display: block;
    width: 80%;
    background: rgba(120,105,255,0.8);
    padding: 10px;
    border-radius: 10px;
    margin: 8px auto;
    color: white;
    text-decoration: none;
    transition: 0.2s;
}

.sidebar a.btn.danger {
    background: rgba(255,70,70,0.9);
    color: white;
}

.sidebar a.btn.danger:hover {
    background: rgba(255,40,40,1);
}


.sidebar a.btn:hover {
    background: rgba(110,95,245,1);
}

.sidebar .danger {
    background: rgba(255,70,70,0.9);
}
.sidebar .danger:hover {
    background: rgba(255,40,40,1);
}


/* MAIN CONTENT */
.main {
    margin-left: 220px;
    padding: 40px;
    display: flex;
    justify-content: center;
}

.box {
    background: rgba(120,105,255,0.18);
    backdrop-filter: blur(6px);
    padding: 30px;
    width: 500px;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(120,105,255,0.3);
}

.box h2 {
    text-align: center;
    margin-bottom: 20px;
}

/* FORM */
label {
    font-size: 13px;
}
input {
    width: 100%;
    margin-top: 4px;
    padding: 8px;
    border-radius: 6px;
    border: none;
    margin-bottom: 10px;
}

button {
    width: 100%;
    padding: 10px;
    background: rgba(120,105,255,0.9);
    border: none;
    border-radius: 6px;
    margin-top: 10px;
    color: white;
    cursor: pointer;
}
button:hover {
    background: rgba(110,95,245,1);
}

.success {
    text-align: center;
    color: #7cff8b;
    margin-bottom: 10px;
}

.back-btn {
    display: block;
    width: 100%;
    text-align: center;
    padding: 10px;
    background: gray;
    border-radius: 6px;
    margin-top: 10px;
    color: white;
    text-decoration: none;
}

.back-btn:hover {
    background: #555;
}


.error {
    text-align: center;
    color: #ff6b6b;
    margin-bottom: 10px;
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
        <img src="<?= $data['profile_image'] ?>">
    </div>

    <div class="username"><?= htmlspecialchars($data['username']) ?></div>

    <a href="user_dashboard.php" class="btn">Dashboard</a>
    <a href="user_profile.php" class="btn">My Profile</a>
    <a href="user_mylist.php" class="btn">My List</a>
    <a href="user_about.php" class="btn">About Us</a>
    <a href="logout.php" class="btn danger">Logout</a>
</div>

<!-- MAIN -->
<div class="main">
<div class="box">
    <h2>Edit Profile</h2>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Full Name</label>
        <input type="text" name="fullname" value="<?= $data['fullname'] ?>" required placeholder="Full Name">

        <label>Email</label>
        <input type="email" name="email" value="<?= $data['email'] ?>" required placeholder="Email">

        <label>Address</label>
        <input type="text" name="address" value="<?= $data['address'] ?>" required placeholder="Address">

        <label>Birthdate</label>
        <input type="date" name="birthdate" value="<?= $data['birthdate'] ?>" required>

        <label>Username</label>
        <input type="text" name="username" value="<?= $data['username'] ?>" required placeholder="Username">

        <label>New Password (optional)</label>
        <input type="password" name="password" placeholder="Leave empty to keep current password">

        <label>Profile Image</label>
        <input type="file" name="profile_image">

        <button type="submit">Save Changes</button>
        <a href="user_dashboard.php" class="back-btn">Back</a>

    </form>

    
</div>
</div>

</body>
</html>
