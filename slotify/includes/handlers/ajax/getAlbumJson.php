<?php
include "../../config.php";

//post method from ajax function
if(isset($_POST['albumId'])){
    $albumId = $_POST['albumId'];

    $query = mysqli_query($con, "SELECT * FROM albums WHERE id = $albumId");
    $resultArray = mysqli_fetch_array($query);

    //convert php data into json in order for javascript to use it
    echo json_encode($resultArray);
}