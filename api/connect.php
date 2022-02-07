<?php

 //allow other websites
 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Allow-Headers: *");


    $dbHost = "localhost";
    $db = "accounting";
    $dbUser = "root";
    $dbPass = "";

    $con = new mysqli($dbHost,$dbUser,$dbPass,$db) or die(mysqli_error($con));


?>