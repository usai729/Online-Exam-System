<?php 
    include "./connect.php";

    session_start();

    if (isset($_SESSION['student'])) {
        $student_ID = $_SESSION['student'];

        $sql_student_id = mysqli_fetch_array(mysqli_query($conn, "SELECT SpID FROM student WHERE stu_id='$student_ID'"))['SpID'];

        try {
            $studentData = mysqli_query($conn, "SELECT student.*, COUNT(results.student) AS totalExamsAttended, FLOOR(AVG(results.score)) AS average FROM student LEFT JOIN results ON student.SpID = results.student WHERE student.SpID='$sql_student_id'");

            while ($rows = mysqli_fetch_assoc($studentData)) {
                echo json_encode($rows);
            }
        } catch (Exception $e) {
            die(-1);
        }
    }
?>