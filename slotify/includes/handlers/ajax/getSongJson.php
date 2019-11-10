<?php
include "../../config.php";

//post method from ajax function
if(isset($_POST['songId'])){
    $songId = $_POST['songId'];

    $query = mysqli_query($con, "SELECT * FROM songs WHERE id = $songId");
    $resultArray = mysqli_fetch_array($query);

    //convert php data into json in order for javascript to use it
    echo json_encode($resultArray);
}