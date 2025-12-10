<?php
include "config.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user"]["id"];
$animeId = $_GET["id"];
$status = $_GET["status"];  // watching or done

mysqli_query($conn, "
    UPDATE user_list 
    SET status='$status' 
    WHERE user_id=$userId AND anime_id=$animeId
");

header("Location: user_mylist.php");
exit;
?>
