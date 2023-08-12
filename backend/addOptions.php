<?php 
    include "./connect.php";

    $settings = isset($_POST['settings']) ? json_decode($_POST['settings']) : null;
    $options = isset($_POST['options']) ? json_decode($_POST['options']) : null;

    $optionsCount = $options ? count((array)$options) : 0;

    $negativeMarking = $settings && isset($settings->negativeMarkingBtn) && $settings->negativeMarkingBtn === "on" ? 1 : 0;
    $negativeMarks = $negativeMarking == 1 ? $settings->negativeMarks : 0;
    $positiveMarks = $settings ? $settings->positiveMarks : 0;
    $startNum = $settings->startNum;
    $endNum = $settings->endNum;

    session_start();
    $examHelperCode = $_SESSION['addExamHelper_code'];
    $examID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT EpID FROM scheduledExams WHERE examID='$examHelperCode'"))['EpID'];
    $questionPaperID = mysqli_fetch_assoc(mysqli_query($conn, "SELECT EQpID FROM examQP WHERE ofExam='$examID'"))['EQpID'];

    $sql_setSettings = "UPDATE examQP SET negativeMarking='$negativeMarking', negativeMarks='$negativeMarks', positiveMarks='$positiveMarks' WHERE EQpID='$questionPaperID'";

    if (mysqli_query($conn, $sql_setSettings)) {
        for ($i = 1; $i <= $optionsCount; $i++) { 
            $variableName = "question_".$i;
            $option = $options->$variableName;

            $sql_setOptions = "INSERT INTO options(exam, questionNum, correctOption, startQuestion, endQuestion) VALUES ($questionPaperID, $i, '$option', '$startNum', '$endNum')";

            if (!mysqli_query($conn, $sql_setOptions)) {
                echo mysqli_error($conn);
            } 
        }

        echo "done";
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
?>
