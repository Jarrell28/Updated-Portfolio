<?php
include "../../config.php";

//post method from ajax function
if(isset($_POST['artistId'])){
    $artistId = $_POST['artistId'];

    $query = mysqli_query($con, "SELECT * FROM artists WHERE id = $artistId");
    $resultArray = mysqli_fetch_array($query);

    //convert php data into json in order for javascript to use it
    echo json_encode($resultArray);
}