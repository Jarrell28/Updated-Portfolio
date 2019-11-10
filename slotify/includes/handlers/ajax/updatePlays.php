<?php
include "../../config.php";

//post method from ajax function
if(isset($_POST['songId'])){
    $songId = $_POST['songId'];

    //adds 1 to plays column in songs
    $query = mysqli_query($con, "UPDATE songs SET plays = plays + 1 WHERE id = $songId");

}