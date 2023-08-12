<?php 
    session_start();

    if (isset($_SESSION['student'])) {
        echo "inSession";
    } else {
        echo "outOfSession";
    }
?>