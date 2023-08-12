<?php 

    session_start();

    if (!isset($_SESSION['admin'])) {
        header("Location: ./login_admin.php");
    }

    //YYY-MM-DD
    date_default_timezone_set("Asia/Kolkata");
	$time = date("H:i:s A");
    $timeW_AP = date("A");
    $date = date("Y-m-d");

	$failure = "";

    $conn = mysqli_connect("localhost", "root", "rootmysql@1#", "panchavati");

    if (!$conn) {
        $failure = "Couldn't connect to server";
    }

    //Exams
    $exam_sql = "SELECT * FROM scheduledExams ORDER BY EpID DESC";
    $exam_query = mysqli_query($conn, $exam_sql);

    //Add exam button
    if (isset($_GET['submit'])) {
    	$arr = array();

        $examName = mysqli_real_escape_string($conn, $_GET['eName']);
        $examCode = $_GET['eCode'];
        $examDate = $_GET['eDate'];
        $examTime = $_GET['eTime'];
        $examEndTime = $_GET['eTimeTo'];
        $examTime_pmAm = $_GET['pm_am'];
        $examTime_pmAm_to = $_GET['pm_am_to'];
        $examNote = mysqli_real_escape_string($conn, $_GET['note']);

        echo $examDate;

        if ($examTime_pmAm != "") {
        	$sql = "INSERT INTO scheduledExams(examID, examSubject_name, examDate, examTime, examTime_to, pm_am, pm_am_to, note) VALUES('$examCode', '$examName', '$examDate', '$examTime', '$examEndTime', '$examTime_pmAm', '$examTime_pmAm_to', '$examNote')";
        	$query = mysqli_query($conn, $sql);

        	if ($query) {
        		session_start();
        		$_SESSION['addExamHelper_code'] = $examCode;

        		header("Location: ./addQuestionPaper.html");
        	} else {
                echo mysqli_error($conn);
            }
        }
        else {
        	$failure = "PM (or) AM cannot be unselected!";
        }
    }

	/*if (isset($_GET['delExamBtn'])) {
		$del_id = $_GET['delExamBtn'];

        echo $del_id;

        $file_to_unlink = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM examQP WHERE ofExam='$del_id'"))['QP'];
		$delSql = "DELETE FROM scheduledExams WHERE EpID='$del_id'";
		
		if (mysqli_query($conn, $delSql)) {
            unlink("../assets/files/".$file_to_unlink);

			header("Refresh:0; url=./scheduleExam.php");
		} else {
            echo mysqli_error($conn);
        }
	}*/
?>

<html>
    <head>
        <title>Add Exam - Panchavati</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

        <script src="../assets/javascript/questionPaper.js"></script>

        <meta name="viewport" content="width=device-width">
    </head>

    <body>
        <div class="container" id="d1">
            <div class="container" id="d2">	
                <h3>Basic Exam Details</h3>
                <div id="form">
                    <form enctype="multipart/form-data" action="" method="GET">
                        <input type="text" class="form-control" placeholder="Exam Name" value="" title="Exam Name" name="eName" id="eName" required>
                        <input type="number" class="form-control" readonly placeholder="Exam Code" value="<?php echo abs(crc32(uniqid())); ?>" title="Exam Code" name="eCode" id="eCode" required>
                        <label for="">Exam Date <span style="font-size: 0.55rem">(DD-MM-YYYY)</span></label>
                        <input type="date" class="form-control" value="<?php echo $date; ?>" title="Exam Date" name="eDate" id="eDate" required>
                        <label for="">Exam From <span style="font-size: 0.55rem">(H:M:S)</span></label>
                        <input type="time" class="form-control" title="Exam Time" name="eTime" id="eTime" step="1" required>
                        <label for="">Ends At <span style="font-size: 0.55rem">(H:M:S)</span></label>
                        <input type="time" class="form-control" title="Exam End Time" name="eTimeTo" id="eTime" step="1" required>
                        <select class="form-control" title="AM (or) PM" name="pm_am" id="eTime">
                            <option value="" disabled selected>AM (or) PM (Exam Start)</option>
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                        <select class="form-control" title="AM (or) PM" name="pm_am_to" id="eTime">
                            <option value="" disabled selected>AM (or) PM (Exam End)</option>
                            <option value="AM">AM</option>
                            <option value="PM">PM</option>
                        </select>
                        <input type="text" name="note" id="note" class="form-control" aria-label="Note (Max 495 Characters)" placeholder="Note (Max 495 Characters)" maxlength="495">
                        <!--<input type="time" class="form-control" value="" title="Max Exam Time" name="eTimeFin" id="eTime" required>
                        <input type="text" class="form-control" value="" title="AM/PM (12:45:00 AM)" placeholder="AM/PM Max Exam Time (12:45:00 AM)" maxlength="2" name="eAM_PM" id="eTime" required>-->
                        <button type="submit" onclick="verify()" class="btn btn-success w-100" value="true" name="submit" id="submitBtn" onclick="verify()">Next</button>
                    </form>

                    <span id="error"><?php echo $failure; ?></span>
                </div>
                <a href="./admin.html">Admin Home</a>
            </div>
            <div id="exams" name="exams">
                <table class="table" id="dTable" style="text-align: center;">
                    <thead>
                        <tr bgcolor='white'>
                            <th scope="col">Exams</th>
                            <th scope="col">Exam Date</th>
                            <th scope="col">Exam Timing</th>
                            <th scope="col">Status</th>
                            <!--<th scope="col">Action</th>-->
                        </tr>
                        <?php 
                            if (mysqli_num_rows($exam_query) > 0) {
                                while ($exams = mysqli_fetch_assoc($exam_query)) {
                                    $primaryId = $exams['EpID'];
                                    $examId = $exams['examID'];
                                    $examName = $exams['examSubject_name'];
                                    $examDate = $exams['examDate'];
                                    $examTime = $exams['examTime'];
                                    $pmOrAm = $exams['pm_am'];
                                    $timeStamp = strtotime($examTime)+10800;
                                    $maxTime = $exams['examTime_to'];
                                    $pmamMax = $exams['pm_am_to'];

                                    /*echo $primaryId." ".strtotime($examTime)."<br>";*/
                                    
                                    if ($date == $examDate && time() < strtotime($maxTime) && strtotime($examTime) < time()) {
                                        echo "
                                            <tr>
                                                <td scope='col'>".$examName."<br>".$examId."</td>
                                                <td scope='col'>".$examDate."</td>
                                                <td scope='col'>".$examTime." ".$pmOrAm."<br><span id='arrow'>-To-</span><br>".$maxTime." ".$pmamMax."</td>
                                                <td scope='col' style='color: green;'>Online</td>
                                                <!--<td scope='col'>
                                                    <form action='#' method='GET'>
                                                        <button type='submit' name='delExamBtn' class='btn btn-secondary btn-sm' value='".$primaryId."' disabled>Delete Exam</button>
                                                    </form>
                                                </td>-->
                                            </tr>
                                            ";
                                    }
                                    else {
                                        echo "
                                            <tr>
                                                <td scope='col' id='examName'>".$examName."<br>".$examId."</td>
                                                <td scope='col' id='examDate'>".$examDate."</td>
                                                <td scope='col' id='examTime'>".$examTime." ".$pmOrAm."<br><span id='arrow'>-To-</span><br>".$maxTime." ".$pmamMax."</td>
                                                <td scope='col' style='color: red;'>Offline</td>
                                                <!--<td scope='col'>
                                                    <form action='#' method='GET'>
                                                        <button type='submit' name='delExamBtn' class='btn btn-primary btn-sm' value='".$primaryId."'>
                                                            Delete Exam
                                                        </button>
                                                    </form>
                                                </td>-->
                                            </tr>
                                            ";
                                    }
                                }
                            }
                        ?>
                    <thead>
                </table>
            </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    </body>
</html>