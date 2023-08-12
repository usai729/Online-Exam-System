<?php 
    include "./globals.php";

    session_start();

    session_destroy();

    if (!isset($_SESSION['student']) || !isset($_SESSION['admin'])) {
        header("Location: ".$url."/index.html");
    }
?>