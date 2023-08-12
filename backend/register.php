<?php 
    include "./connect.php";

    if (isset($_POST['submit'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $phno = $_POST['phoneNumber'];
        $course = $_POST['course'];
        $pwd = mysqli_real_escape_string($conn, $_POST['password']);

        $pwd = md5($pwd);
        $userID = ($phno)."STU".strtoupper($course);

        session_start();
        $_SESSION['registerer'] = $userID;

        $dup_query = mysqli_query($conn, "SELECT * FROM student WHERE stu_id='$userID'");
        
        if (mysqli_num_rows($dup_query) != 0) {
            header("Location: ../frontend/register.html?e=Student-With-The-Phone-Number-Already-Exists");
        } else {
            if (mysqli_query($conn, "INSERT INTO student(stu_id, stu_pwd, stu_name, course, blocked) VALUES('$userID', '$pwd', '$name', '$course', 0)")) {
                header("Location: ../frontend/showUserID.php");
            }
        }
    }
?>