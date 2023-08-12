<?php
    include "./connect.php";

    session_start();

    $student = $_SESSION['student'];

    $selectedOptions = Array();

    $noOfQuestions = $_POST['noOfQuestions'];
    $paperID = $_POST['paperID'];
    $examID = $_POST['examID'];
    $QCount = $_POST['questionCount'];
    $negativeMarks = 0;

    $positiveScore = 0;
    $negativeScore = 0;
    $totalScore = 0;

    $sql_negativeMarks = "SELECT negativeMarking, negativeMarks, positiveMarks FROM examQP WHERE EQpID='$paperID'";
    $negativeMarksQueryResult = mysqli_query($conn, $sql_negativeMarks);

    $negativeMarksQueryResultAssoc = mysqli_fetch_assoc($negativeMarksQueryResult);

    $negativeMarksBool = $negativeMarksQueryResultAssoc['negativeMarking'];
    $positiveMarks = $negativeMarksQueryResultAssoc['positiveMarks'];

    if ($negativeMarksBool) {
        $negativeMarks = $negativeMarksQueryResultAssoc['negativeMarks'];
    } else {
        $negativeMarks = 0;
    }

    for ($i = 1; $i <= $noOfQuestions; $i++) { 
        $questionNum = "optionSelection_".$i;
        $selection = isset($_POST[$questionNum]) ? $_POST[$questionNum] : "blank";

        array_push($selectedOptions, $selection);
    }
        
    $file = fopen("../assets/files/".$paperID."-key.txt", 'w');
    $fileName = $paperID."-key.txt";

    $dup_arr = Array();

    for ($j = 1; $j <= sizeof($selectedOptions)-1; $j++) {
        $questionOpt = @mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM options WHERE questionNum='$j' AND exam='$paperID'"));

        for ($jsub = $questionOpt['startQuestion']; $jsub <= $questionOpt['endQuestion'] ; $jsub++) { 
            if (in_array($jsub, $dup_arr)) {
                continue;
            } else {
                array_push($dup_arr, $jsub);
                fwrite($file, "Question ".$jsub.": ".$questionOpt['correctOption']."\n");
            }
        }
    }

    fwrite($file, "+".$positiveMarks."\t".$negativeMarks);

    for ($k = 0; $k <= sizeof($selectedOptions)-1; $k++) {
        $questionOpt = $k > 0 ? @mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM options WHERE questionNum='$k' AND exam='$paperID'"))['correctOption'] : @mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM options WHERE questionNum='1' AND exam='$paperID'"))['correctOption'];

        if ($selectedOptions[$k] != "blank") {
            if ($selectedOptions[$k] == $questionOpt) {
                $totalScore += $positiveMarks;
            } else if ($selectedOptions[$k] != $questionOpt) {
                $totalScore += $negativeMarks;
            }
        } else {
            $totalScore += 0;
        }
    }
    
    $max_marks = sizeof($selectedOptions)*$positiveMarks;

    $sql_student_id = mysqli_fetch_array(mysqli_query($conn, "SELECT SpID FROM student WHERE stu_id='$student'"))['SpID'];

    $sql_resultInsertion = "INSERT INTO results(exam, student, score, max_marks) VALUES('$examID', '$sql_student_id', '$totalScore', '$max_marks')";
    $sql_insertAnswerKey = "UPDATE answerKeys SET keyFile='$fileName' WHERE key_for='$examID'";

    if (mysqli_query($conn, $sql_resultInsertion) && mysqli_query($conn, $sql_insertAnswerKey)) {
        echo "ok";
    } 
?>
