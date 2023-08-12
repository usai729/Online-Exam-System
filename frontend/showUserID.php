<?php 
    include "../backend/globals.php";

    session_start();

    /*f (!isset($_SESSION['registerer'])) {
        header("Location: ".$url."/login_student.php");
    }*/

    echo "Your user ID is : ".$_SESSION['registerer'];

    session_destroy();

    echo "<br><a href='./login_student.php'>Login</a>";
?>