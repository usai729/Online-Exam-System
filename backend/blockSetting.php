<?php 
    include "./connect.php";

    $spid = $_POST['stu'];

    $sqlBlocked = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM student WHERE SpID='$spid'"))['blocked'] == 1 ? true : false;

    if ($sqlBlocked) {
        if (mysqli_query($conn, "UPDATE student SET blocked=0 WHERE SpID=$spid")) {
            echo "unblocked";
        } 
    } else {
        if (mysqli_query($conn, "UPDATE student SET blocked=1 WHERE SpID=$spid")) {
            echo "blocked";
        }
    }
?>