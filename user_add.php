<?php
include "config.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId   = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];   // <-- GET USERNAME
$fullname = $_SESSION['user']['fullname'];   // <-- GET FULL NAME
$animeId  = $_GET['id'] ?? null;

if ($animeId) {

    // Check if anime already exists in user's list
    $check = mysqli_query($conn, 
        "SELECT * FROM user_list WHERE user_id=$userId AND anime_id=$animeId"
    );

    if (mysqli_num_rows($check) > 0) {
        $status = "exists";
    } else {

        // Get anime title
        $animeQuery = mysqli_query($conn, "SELECT title FROM anime WHERE id=$animeId");
        
        if ($animeRow = mysqli_fetch_assoc($animeQuery)) {
            $animeTitle = mysqli_real_escape_string($conn, $animeRow['title']);
            $username   = mysqli_real_escape_string($conn, $username);
            $fullname   = mysqli_real_escape_string($conn, $fullname);

            // Insert with username + full name
            mysqli_query($conn, 
                "INSERT INTO user_list (user_id, username, full_name, anime_id, anime_title) 
                 VALUES ($userId, '$username', '$fullname', $animeId, '$animeTitle')"
            );

            $status = "added";
        } else {
            $status = "error";
        }
    }
}

$redirect = $_GET['from'] ?? 'user_dashboard.php';
$separator = (parse_url($redirect, PHP_URL_QUERY) ? '&' : '?');
header("Location: $redirect{$separator}status=$status");
exit;
?>
