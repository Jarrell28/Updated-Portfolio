<?php
//check whether the url request was sent by ajax or manually entered by user


//if it was sent by ajax
//every ajax request contains HTTP_X_REQUESTED_WITH
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
    //if loaded with ajax, dont want to include header and footer. want to only change
    //main content to keep the same play bar and side nav
    include "includes/config.php";
    include "includes/classes/User.php";
    include "includes/classes/Artist.php";
    include "includes/classes/Album.php";
    include "includes/classes/Song.php";
    include "includes/classes/Playlist.php";

    //need to make userLoggedIn variable available to the ajax loaded content page
    //to access the users personal playlists
    if(isset($_GET['userLoggedIn'])){
        $userLoggedIn = new User($con, $_GET['userLoggedIn']);
    } else {
        echo "username variable was not passed into page. Check the openPage JS function";
    }

} else {
    //if manually entered by user, must include header and footer
    include("includes/header.php");
    include("includes/footer.php");

    //gets url
    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    //prevents from loading extra pages
    exit();

}