<?php
include "config.php";
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($res);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user"] = $user;

        if ($user["role"] == "admin") {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - TrackiNime</title>
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
            align-items: center;
        }

        .login-box {
            width: 360px;
            background: ;
            backdrop-filter: ;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 0 16px rgba(103, 119, 209, 0.3);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
            color: white;
        }

        label {
            color: white;
            font-size: 13px;
            margin-top: 8px;
            display: block;
        }

        input {
            width: 95%;
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

        .error {
            text-align: center;
            color: #ff4b4b;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>
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
        <div class="login-box">
            <h1>Login</h1>

            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

            <form action="" method="POST">
                <label>Username</label>
                <input type="text" name="username" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button type="submit">Login</button>
            </form>

            <div class="link">
                No account yet? <a href="register.php">Register here.</a>
            </div>
        </div>
    </div>

</body>
</html>
