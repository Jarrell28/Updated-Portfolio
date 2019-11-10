<?php
    ob_start();
    session_start();

    //google php timezones for all
    $timezone = date_default_timezone_set("Europe/London");

    $con = mysqli_connect("localhost", "miahou28", "rell2882", "slotify1");

    //checks errors if cant connect to db
    if(mysqli_connect_errno()){
        echo "Failed to connect: " . mysqli_connect_errno();

    }