<?php 
  date_default_timezone_set("Asia/Kolkata");
	$time = date("H:i:s A");
  $timeW_AP = date("A");
  $date = date("Y-m-d");

  session_start();
  $student = $_SESSION['student'];

	$failure = "";

  $conn = mysqli_connect("localhost", "root", "rootmysql@1#", "panchavati");

  if (!$conn) {
      $failure = "Couldn't connect to server";
  }

  if (!isset($_SESSION['student'])) {
    $failure = "You are not logged in";

    header("Location: ./login_student.php");
  }

  $examID = $_GET['takeExamBtn'];

  $studentID = mysqli_fetch_array(mysqli_query($conn, "SELECT SpID FROM student WHERE stu_id='$student'"))['SpID'];

  if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM results WHERE exam='$examID' AND student='$studentID'")) != 0) {
    echo "
      <script>
        if (window.confirm('You\'ve already written the exam, you cannot write this exam again.')) {
          window.close();
        } else {
          window.close();
        }
      </script>
    ";
  }

  $sql_script_1 = "SELECT scheduledExams.*, examQP.* FROM scheduledExams INNER JOIN examQP ON scheduledExams.EpID=examQP.ofExam WHERE scheduledExams.EpID='$examID'";
  $sql_query_1 = (mysqli_query($conn, $sql_script_1));

  $result = mysqli_fetch_assoc($sql_query_1);

  $paperID = $result['EQpID']; 

  $note = isset($result['note']) ? $result['note'] : "No note available";

  $file = "http://localhost:8000/assets/files/".$result['QP'];
  echo "<span style='float: right; font-size: 0.6rem; color: gray'>U Sai Nath Rao</span>";
  
  $examStartTime = $result['examTime'];
  $examEndTime = $result['examTime_to'];

  $examStartArray = explode(":", $examStartTime);
  $examEndArray = explode(":", $examEndTime);
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.min.js"></script>

    <title>Exam</title>

    <script>
      var startTime = new Date();
      startTime.setHours(<?php echo intval($examStartArray[0]); ?>)
      startTime.setMinutes(<?php echo intval($examStartArray[1]); ?>)
      startTime.setSeconds(<?php echo intval($examStartArray[2]); ?>)

      var endTime = new Date();
      endTime.setHours(<?php echo intval($examEndArray[0]); ?>)
      endTime.setMinutes(<?php echo intval($examEndArray[1]); ?>)
      endTime.setSeconds(<?php echo intval($examEndArray[2]); ?>)

      var timeLeft = Math.floor((endTime.getTime() - startTime.getTime()) / 1000); // time left in seconds

      function formatTime(seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds % 3600) / 60);
        var remainingSeconds = seconds % 60;

        minutes = minutes < 10 ? "0" + minutes : minutes;
        remainingSeconds = remainingSeconds < 10 ? "0" + remainingSeconds : remainingSeconds;

        return hours + ":" + minutes + ":" + remainingSeconds;
      }

      function updateTime() {
        var now = new Date();
        var timeLeft = Math.floor((endTime.getTime() - now.getTime()) / 1000);
        document.getElementById("timer").innerHTML = formatTime(timeLeft);

        if (timeLeft <= 0) {
          document.getElementById("submit").click();
        } else {
          setTimeout(updateTime, 1000);
        }
      }

      setTimeout(updateTime, 1000);

      var pdfName = '<?php echo $result['QP'] ?>';
      var url = "https://f9cb-183-83-225-130.ngrok-free.app/assets/files/"+pdfName;
    </script>

    <script src="http://localhost:8000/assets/javascript/examPage.js"></script>

    <style>
      body {
        margin: 0;
        font-family: "Roboto";
        overflow: hidden;
      }

      /* Style the left and right column */
      .left-column {
        width: 70%;
        float: left;
        height: 100%;
      }
      
      .right-column {
        width: 30%;
        float: left;
        height: 100%;
      }

      .right-scroll {
        height: 100%;
        overflow-y: scroll;
      }

      .left-column, .right-column {
        height: 100%;
      }

      /* Clear floats after the columns */
      .row:after {
        content: "";
        display: table;
        clear: both;
      }

      .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: #3e8e41;
        color: white;
        text-align: center;
        padding: 5px;
      }

      #clearBtn {
        cursor: pointer;
        transition: 0.2s;
        margin-left: 5px;
      }
      #clearBtn:hover {
        text-decoration: underline;
      }

      /* Responsive layout - make the columns stack on top of each other */
      @media screen and (max-width: 600px) {
        .left-column, .right-column {
          width: 100%;
        }

        .left-column {
          height: 60%;
        }

        .right-column {
          height: 40%;
        }

        .footer {
          padding: 2px;
        }
      }
    </style>
  </head>

  <body>
    <div class="row">
      <div class="left-column" id="left"> 
        <<iframe src="https://docs.google.com/viewer?url=<?php echo 'http://panchavati.in/uploads/m29.pdf'; ?>&embedded=true" width="100%" height="100%"></iframe>
      </div>

      <div class="right-column">
        <div class="right-scroll">

        <br>

          <b><span id="timer"></span> Remaining</b>
          <div id="buttons" style="display: flex;"></div>
          <form onsubmit="return false" method="POST" name="examform" id="exam-form" style="margin-top: 10px">
              <?php 
                $sql_getOptionSet = "SELECT *, startQuestion, endQuestion, count(*) AS count FROM options WHERE exam='$paperID'";
                $result = mysqli_query($conn, $sql_getOptionSet);
                
                $arr = Array();

                while ($rows = mysqli_fetch_assoc($result)) {
                  $noOfRows = $rows['count'];
                  $question = $rows['questionNum'];
                  $startQ = $rows['startQuestion'];
                  $endQ = $rows['endQuestion'];

                  for ($k = $startQ; $k <= $endQ; $k++) {
                    for ($i = 1; $i <= $noOfRows; $i++) {
                      echo "
                        <div style='display: block; margin: 25px'>
                          <div style='display: flex;'>
                            <label>".(($k+$i)-1).".</label>
                        ";

                      echo "
                        <input type='radio' id='".$i."_".(($k+$i)-1)."' name='optionSelection_".$i."' value='Opt 1'>
                        <input type='radio' id='".$i."_".(($k+$i)-1)."' name='optionSelection_".$i."' value='Opt 2'>
                        <input type='radio' id='".$i."_".(($k+$i)-1)."' name='optionSelection_".$i."' value='Opt 3'>
                        <input type='radio' id='".$i."_".(($k+$i)-1)."' name='optionSelection_".$i."' value='Opt 4'>
                        <button value='optionSelection_".$i."' onclick='clearSelection(this)' id='clearBtn' style='border: none; background: transparent;'>&#10005;</button>
                      ";

                      echo "
                        </div>
                        </div>
                      ";

                      array_push($arr, $k);
                    }

                    if (in_array($k, $arr)) {
                        break;
                    }
                  }

                  echo '
                    <input type="hidden" name="noOfQuestions" value="'.$noOfRows.'" readonly>
                    <input type="hidden" name="paperID" value="'.$paperID.'" readonly>
                    <input type="hidden" name="examID" value="'.$examID.'" readonly>
                    <input type="hidden" name="questionCount" value="'.$examID.'" readonly>
                  ';
                }
              ?>
              <div style="display: flex; justify-content: space-between; margin-bottom: 70px">
                <button id="submit" name="submit" onclick="submitOMR()" style="margin: 10px;">Submit OMR</button>
                <button type="reset">Reset </button>
              </div>
          </form>
        </div>
        <div class="footer">
            <?php echo "<p style=''>".$note ."</p>" ?>
        </div>
      </div>
    </div>
  </body>
</html>
