<?php 
    include "./connect.php";

    header('X-Frame-Options: ALLOW-FROM http://localhost:8000');

    session_start();
    $examHelperCode = $_SESSION['addExamHelper_code'];
    $examID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT EpID FROM scheduledExams WHERE examID='$examHelperCode'"))['EpID'];

   $sql_script_1 = "SELECT * FROM examQP WHERE ofExam='$examID'";
    $sql_query_1 = mysqli_fetch_assoc(mysqli_query($conn, $sql_script_1))['QP'];

    $file = "/assets/files/".$sql_query_1;

    echo $file;
?>