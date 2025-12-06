<?php
include "config.php";
session_start();

$id = $_GET["id"];

mysqli_query($conn, "DELETE FROM anime WHERE id=$id");
mysqli_query($conn, "DELETE FROM user_list WHERE anime_id=$id");

header("Location: admin_dashboard.php");
?>
