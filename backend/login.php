<?php 
    include "./connect.php";

    $from_page = explode('/', $_SERVER['HTTP_REFERER'])[4];
    $id = mysqli_real_escape_string($conn, $_POST['userid']);
    $pwd = mysqli_real_escape_string($conn, $_POST['password']);

    $date = date("Y-m-d");
    $time = date("g:i:s");

    if ($from_page == "login_admin.php" || $from_page == "login_admin.php?login_failed=true") {
        $sql_admin_login = mysqli_query($conn, "SELECT * FROM admin WHERE admin_id='$id' AND admin_pwd='$pwd'");
        
        if ($sql_admin_login) {
            if (mysqli_num_rows($sql_admin_login) == 1) {
                session_start();

                $_SESSION['admin'] = $id;

                header("Location: ../frontend/admin.html");
            } else {
                header("Location: ".$_SERVER['HTTP_REFERER']."?login_failed=true");
                exit();
            } 
        } else {
            echo mysqli_error($conn);
        }
    } else {
        $pwd = md5($pwd);

        $sql_student_login = mysqli_query($conn, "SELECT * FROM student WHERE stu_id='$id' AND stu_pwd='$pwd'");
        
        if ($sql_student_login) {
            if (mysqli_num_rows($sql_student_login) == 1) {
                session_start();

                $_SESSION['student'] = $id;

                $sqlBlocked = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM student WHERE stu_id='$id'"))['blocked'] == 1 ? true : false;

                if (!$sqlBlocked) {
                    $sql_student_id = mysqli_fetch_array(mysqli_query($conn, "SELECT SpID FROM student WHERE stu_id='$id'"))['SpID'];
                    mysqli_query($conn, "INSERT INTO student_login(loginBy, login_date, login_time) VALUES('$sql_student_id', '$date', '$time')");
                        
                    //'5', 'S001', 'password1', 'John Doe', 'Computer Science', '1'
                    
                    header("Location: ../frontend/home.html");
                } else {
                    session_destroy();

                    header("Location: ".$_SERVER['HTTP_REFERER']);
                }
            } else {
                header("Location: ".$_SERVER['HTTP_REFERER']."?login_failed=true");
                exit();
            } 
        } else {
            echo mysqli_error($conn);
        }
    }

    /*
    openssl_encrypt($_POST['password'], "AES-128-CTR", "assignments608621861023", 0, 1234567891011121)
    openssl_decrypt($pwd, "AES-128-CTR", "assignments608621861023", 0, 1234567891011121)
    */
?>