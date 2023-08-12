<?php 
    $conn = mysqli_connect("localhost", "root", "rootmysql@1#", "panchavati");

    if (!$conn) {
        $failure = "Couldn't connect to server";
    }

    $array = array();

    //Exams
    $exam_sql = "SELECT * FROM scheduledExams ORDER BY EpID DESC";
    $exam_query = mysqli_query($conn, $exam_sql);

    if (mysqli_num_rows($exam_query) > 0) {
        while ($exams = mysqli_fetch_assoc($exam_query)) {
            array_push($array, $exams);
        }
    }

    print_r(json_encode($array));
?>