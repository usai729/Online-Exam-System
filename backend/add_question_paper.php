<?php 
    include "./connect.php";

    session_start();

    if (isset($_POST['submit'])) {
        $fileName = $_FILES['questionPaperFile']['name'];
        $fileTmp = $_FILES['questionPaperFile']['tmp_name'];
        $solutions = $_FILES['solutionFile'];

        $fileNameFinal = uniqid().$fileName;

        $examHelperCode = $_SESSION['addExamHelper_code'];

        $examID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT EpID FROM scheduledExams WHERE examID='$examHelperCode'"))['EpID'];

        if (move_uploaded_file($fileTmp, dirname(__DIR__)."/assets/files/".$fileNameFinal)) {
            $sql_store_file = "INSERT INTO examQP(ofExam, QP) VALUES('$examID', '$fileNameFinal')";

            if (mysqli_query($conn, $sql_store_file)) {
                if (!(isset($solutions) && isset($solutions['tmp_name']) && !empty($solutions['tmp_name']))) {
                    header("Location: ../frontend/setOptions.html");
                } else {
                    $solutionsFileNameFinal = uniqid().$solutions['name'];
                    $solutionsTmp = $solutions['tmp_name'];

                    if (move_uploaded_file($solutionsTmp, dirname(__DIR__)."/assets/files/".$solutionsFileNameFinal)) {
                        if (mysqli_query($conn, "INSERT INTO answerKeys(key_for, solFile) VALUES('$examID', '$solutionsFileNameFinal')")) {
                            header("Location: ../frontend/setOptions.html");
                        }
                    }
                }
            }
        }
    }
?>