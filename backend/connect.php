<?php 
    date_default_timezone_set("Asia/Kolkata");

    $failure = "";

    $conn = mysqli_connect("localhost", "root", "rootmysql@1#", "panchavati");

    if (!$conn) {
        $failure = "Couldn't connect to server";
        echo $failure;

        exit();
    }
?>