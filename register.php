
<?php
include "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = $_POST["role"];

    // COMMON FIELDS
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // IMAGE UPLOAD
    $profile = "";
    if (!empty($_FILES["profile_image"]["name"])) {
        $target_dir = "uploads/";
        $profile = $target_dir . basename($_FILES["profile_image"]["name"]);
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $profile);
    }

    if ($role === "admin") {
        $adminCode = $_POST["adminCode"];
        // Check if admin code is correct
        if ($adminCode !== "admin123") {
            $error = "Invalid Admin Code! Only authorized users can register as Admin.";
        } else {
            // Insert admin into DB
            $sql = "INSERT INTO users (fullname, email, username, password, role, profile_image) 
                    VALUES ('$fullname', '$email', '$username', '$password', '$role', '$profile')";
            if (mysqli_query($conn, $sql)) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Error registering admin. Please try again.";
            }
        }
    } else {
        // Insert normal user into DB
        $sql = "INSERT INTO users (fullname, email, username, password, role, profile_image) 
                VALUES ('$fullname', '$email', '$username', '$password', '$role', '$profile')";
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Error registering user. Please try again.";
        }
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Register - TrackiNime</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('assets/bg.gif') no-repeat center center fixed;
            background-size: cover;
            font-family: "Poppins", sans-serif;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* LEFT SIDE */
        .left-side {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 40px;
            backdrop-filter: blur(2px);
        }

        .logo-circle {
            width: 330px;
            height: 330px;
            border-radius: 50%;
            background: rgba(255,255,255,0.18);
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            margin-bottom: 20px;
            border: 2px solid rgba(255,255,255,0.5);
        }

        .logo-circle img {
            width: 80%;
        }

        .description {
            margin-top: 14px;
            font-size: 16px;
            max-width: 380px;
            line-height: 1.5;
            color: black;
            text-shadow: 0 0 12px rgba(0,0,0,0.8);
        }

        /* RIGHT SIDE */
        .right-side {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center; /* changed from center to move box higher */
        }

        .register-box {
            width: 360px;
            background: ; /* transparent blue */
            backdrop-filter: ;
            padding: 25px;
            border-radius: ;
            box-shadow: 0 0 16px rgba(103, 119, 209, 0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 22px;
            color: white;
        }

        .role-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .role-btn {
            width: 40%;
            padding: 10px;
            background: rgba(120,105,255,0.8);
            color: white;
            border: none;
            border-radius: 100px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            text-align: center;
        }

        .role-btn:hover {
            background: rgba(110,95,245,1);
            transform: scale(1.04);
        }

        label {
            color: white;
            font-size: 13px;
            margin-top: 8px;
            display: block;
        }

        input {
            width: 100%;
            padding: 8px;
            font-size: 12px;
            margin-top: 3px;
            border-radius: 5px;
            border: 1px solid gray;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            background: rgba(120,105,255,0.9);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: rgba(110,95,245,1);
            transform: scale(1.02);
        }

        .terms {
            margin-top: 8px;
            font-size: 11px;
            color: white;
        }

        .terms input {
            width: auto;
            margin-right: 4px;
        }

        .link {
            text-align: center;
            margin-top: 12px;
            font-size: 12px;
            color: white;
        }

        .link a {
            color: #e8e6ff;
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }

        .error {
            text-align: center;
            color: #ff4b4b;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>

    <script>
        function showUserForm() {
            document.getElementById("userForm").style.display = "block";
            document.getElementById("adminForm").style.display = "none";
        }

        function showAdminForm() {
            document.getElementById("adminForm").style.display = "block";
            document.getElementById("userForm").style.display = "none";
        }
    </script>

</head>
<body>

<!-- LEFT SIDE -->
<div class="left-side">
    <div class="logo-circle">
        <img src="assets/logo.png">
    </div>

    <div class="description">
        Trackinime is your ultimate anime companion, helping you track, organize, and stay updated on all your favorite anime series in one place.
    </div>
</div>

<!-- RIGHT SIDE -->
<div class="right-side">
    <div class="register-box">

        <h1>Register</h1> <br><br>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <div class="role-buttons">
            <div class="role-btn" onclick="showUserForm()">Register as User</div> 
            <div class="role-btn" onclick="showAdminForm()">Register as Admin</div>
        </div>

        <!-- USER FORM -->
        <form id="userForm" class="hidden" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="role" value="user">

            <label>Full Name</label>
            <input type="text" name="fullname" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Address</label>
            <input type="text" name="address" required>

            <label>Birthdate</label>
            <input type="date" name="birthdate" required>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Profile Image</label>
            <input type="file" name="profile_image">

            <div class="terms">
                <input type="checkbox" required>
                By registering, you agree to TrackiNime’s Terms, Data Policy, and Privacy Rules.
            </div>

            <button type="submit">Create User Account</button>
        </form>

        <!-- ADMIN FORM -->
        <form id="adminForm" class="hidden" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="role" value="admin">

            <label>Full Name</label>
            <input type="text" name="fullname" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Admin Code</label>
            <input type="text" name="adminCode" placeholder="admin code" required>

            <label>Profile Image</label>
            <input type="file" name="profile_image">

            <div class="terms">
                <input type="checkbox" required>
                By registering, you agree to TrackiNime’s Terms, Admin Policy, and Security Guidelines.
            </div>

            <button type="submit">Create Admin Account</button>
        </form>

        <br>
        <div class="link">
            Already have an account? <a href="login.php">Sign in</a>
        </div>

    </div>
</div>

</body>
</html>
