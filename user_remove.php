<?php
include "config.php";
session_start();

$animeId = $_GET["id"];
$userId = $_SESSION["user"]["id"];

mysqli_query($conn, "DELETE FROM user_list WHERE user_id=$userId AND anime_id=$animeId");

header("Location: user_mylist.php");
?>
