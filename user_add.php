<?php
include "config.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$animeId = $_GET['id'] ?? null;

if ($animeId) {
    // Check if anime is already in user's list
    $check = mysqli_query($conn, "SELECT * FROM user_list WHERE user_id=$userId AND anime_id=$animeId");

    if (mysqli_num_rows($check) > 0) {
        // Already in list
        $status = "exists";
    } else {
        // Fetch anime title
        $animeQuery = mysqli_query($conn, "SELECT title FROM anime WHERE id=$animeId");
        if ($animeRow = mysqli_fetch_assoc($animeQuery)) {
            $animeTitle = mysqli_real_escape_string($conn, $animeRow['title']);
            // Insert into user_list with title
            mysqli_query($conn, "INSERT INTO user_list (user_id, anime_id, anime_title) VALUES ($userId, $animeId, '$animeTitle')");
            $status = "added";
        } else {
            // Anime not found
            $status = "error";
        }
    }
}

$redirect = $_GET['from'] ?? 'user_dashboard.php';
$separator = (parse_url($redirect, PHP_URL_QUERY) ? '&' : '?');
header("Location: $redirect{$separator}status=$status");
exit;
?>
