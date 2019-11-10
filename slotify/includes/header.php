<?php
include "includes/config.php";
include "includes/classes/User.php";
include "includes/classes/Artist.php";
include "includes/classes/Album.php";
include "includes/classes/Song.php";
include "includes/classes/Playlist.php";

if(isset($_SESSION['userLoggedIn'])){
    $userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
    $username = $userLoggedIn->getUsername();
    //set userLoggedIn in javascript? important? make it in single quotes
    echo "<script>userLoggedIn = '$username'</script>";
} else {
    header("Location: register.php");
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slotify</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>




<div id="mainContainer">

    <div id="topContainer">

        <?php include "includes/navBarContainer.php"?>

        <div id="mainViewContainer">

            <div id="mainContent">
