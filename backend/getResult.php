<?php 
    include "./connect.php";

    session_start();

    $studentID = $_SESSION['student'];  
    $sql_student_id = mysqli_fetch_array(mysqli_query($conn, "SELECT SpID FROM student WHERE stu_id='$studentID'"))['SpID'];

    $results = array();

    $sql_student_results = mysqli_query($conn, "SELECT results.score, results.max_marks, scheduledExams.examSubject_name, scheduledExams.examID, scheduledExams.examDate, answerKeys.keyFile, answerKeys.solFile FROM results LEFT JOIN scheduledExams ON scheduledExams.EpID = results.exam LEFT JOIN answerKeys ON answerKeys.key_for = scheduledExams.EpID WHERE results.student='$sql_student_id' ORDER BY results.RpID DESC");

    if ($sql_student_results) {
        while ($row = mysqli_fetch_assoc($sql_student_results)) {
            $results[] = $row;
        }
        echo json_encode($results);
    } else {
        echo json_encode(array('error' => 'Failed to retrieve results'));
    }
?>
