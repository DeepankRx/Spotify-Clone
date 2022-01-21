<?php
    //turns on output buffering 
    ob_start();
    // session_start();
    // session_destroy();
    $timezone = date_default_timezone_set("Asia/Kolkata"); 

    $host = "localhost:4306";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "slotify";
    $conn =mysqli_connect($host, $dbUsername, $dbPassword, $dbname);
    if(mysqli_connect_errno())
    {
        echo "Failed To Connect : ".mysqli_connect_error($conn);
    }
?>