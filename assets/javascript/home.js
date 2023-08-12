function getStudentBasicDetails() {
  fetch("../backend/homepageinfo.php")
    .then((r) => {
      return r.json();
    })
    .then((re) => {
      document.getElementById("stu_name").innerHTML = "Hi, " + re.stu_name;
      document.getElementById("stu_id").innerHTML = re.stu_id;
      document.getElementById("examsAttended").innerHTML =
        re.totalExamsAttended + " Exam(s) attended";
      document.getElementById("overall_scoring").innerHTML =
        re.average != null
          ? re.average + " avg of all exams attended"
          : "0 avg of all exams attended";
    })
    .then(() => {
      fetch("../backend/getScheduledExams.php")
        .then((scheduledExamsP1) => {
          return scheduledExamsP1.json();
        })
        .then((ScheduledExams) => {
          var examsTable = document.getElementById("exams");

          for (let i = 0; i <= ScheduledExams.length - 1; i++) {
            var row = document.createElement("tr");
            var actBtn = document.createElement("button");
            actBtn.innerHTML = "Take Exam";
            actBtn.setAttribute("id", ScheduledExams[i].EpID);
            actBtn.setAttribute("name", "takeExamBtn");

            var stringTimeFrom = ScheduledExams[i].examTime;
            let timeFromArray = stringTimeFrom.toString().split(":");

            var stringTimeTo = ScheduledExams[i].examTime_to;
            let timeTillArray = stringTimeTo.toString().split(":");

            var examDate = new Date(ScheduledExams[i].examDate);
            var dateNow = new Date();

            var examTime = new Date();
            examTime.setHours(parseInt(timeFromArray[0]));
            examTime.setMinutes(parseInt(timeFromArray[1]));
            examTime.setSeconds(parseInt(timeFromArray[2]));

            var endTime = new Date();
            endTime.setHours(parseInt(timeTillArray[0]));
            endTime.setMinutes(parseInt(timeTillArray[1]));
            endTime.setSeconds(parseInt(timeTillArray[2]));

            var currentTime = dateNow.getTime();

            if (examDate.getDate() != dateNow.getDate()) {
              actBtn.setAttribute("disabled", true);
              actBtn.removeAttribute("id");
            } else {
              if (
                currentTime >= examTime.getTime() &&
                currentTime <= endTime.getTime()
              ) {
                actBtn.removeAttribute("disabled");

                actBtn.setAttribute("onclick", "openExamWindow(this)");
              } else {
                actBtn.setAttribute("disabled", true);
                actBtn.removeAttribute("id");
              }
            }

            var td = document.createElement("td");
            td.innerHTML = ScheduledExams[i].examID;

            var td1 = document.createElement("td");
            td1.innerHTML = ScheduledExams[i].examSubject_name;

            var td2 = document.createElement("td");
            td2.innerHTML = ScheduledExams[i].examDate;

            var td3 = document.createElement("td");
            td3.innerHTML =
              ScheduledExams[i].examTime +
              "<br>-To-<br>" +
              ScheduledExams[i].examTime_to;

            var td4 = document.createElement("td");
            td4.appendChild(actBtn);

            row.appendChild(td);
            row.appendChild(td1);
            row.appendChild(td2);
            row.appendChild(td3);
            row.appendChild(td4);

            if (examDate.getDate() == dateNow.getDate()) {
              examsTable.append(row);
            }
          }
        })
        .then(() => {
          fetch("../backend/getResult.php")
            .then((r1) => {
              return r1.json();
            })
            .then((r2) => {
              var resultsTable = document.getElementById("results");
              var dateNow = new Date();

              for (let i = 0; i < r2.length; i++) {
                const element = r2[i];
                var examDate = new Date(r2[i].examDate);

                var tr_r = document.createElement("tr");
                tr_r.setAttribute("id", "results_tr");

                var td_id = document.createElement("td");
                td_id.innerHTML = i;
                tr_r.appendChild(td_id);

                var td_examID = document.createElement("td");
                td_examID.innerHTML = r2[i].examID;
                tr_r.appendChild(td_examID);

                var td_examName = document.createElement("td");
                td_examName.innerHTML = r2[i].examSubject_name;
                tr_r.appendChild(td_examName);

                var td_examDate = document.createElement("td");
                td_examDate.innerHTML = r2[i].examDate;
                tr_r.appendChild(td_examDate);

                var td_score = document.createElement("td");
                td_score.innerHTML = r2[i].score + "/" + r2[i].max_marks;
                tr_r.appendChild(td_score);

                if (examDate.getDate() != dateNow.getDate()) {
                  var td_key = document.createElement("td");
                  var anchor = document.createElement("a");

                  anchor.href = "../assets/files/" + r2[i].keyFile;
                  anchor.innerHTML = "Download key";
                  anchor.download = "../assets/files/" + r2[i].keyFile;
                  anchor.style.color = "white";
                  td_key.appendChild(anchor);

                  if (r2[i].solFile != null && r2[i].solFile != undefined) {
                    var anchor = document.createElement("a");
                    anchor.href = "../assets/files/" + r2[i].solFile;
                    anchor.innerHTML = "<br>*<br>Download Solutions";
                    anchor.download = "../assets/files/" + r2[i].keyFile;
                    anchor.style.color = "white";
                    td_key.appendChild(anchor);
                  }

                  tr_r.appendChild(td_key);
                } else {
                  var td_key = document.createElement("td");
                  var anchor = document.createElement("a");
                  anchor.href = "";
                  anchor.innerHTML = "N/A";
                  anchor.download = "";
                  anchor.style.color = "white";
                  anchor.style.textDecoration = "None";
                  anchor.style.cursor = "None";
                  td_key.appendChild(anchor);
                  tr_r.appendChild(td_key);
                }

                resultsTable.appendChild(tr_r);
              }
            });
        });
    });
}

function openExamWindow(btn) {
  var btn_val = btn.id;
  var sWidth = window.screen.width;
  var sHeight = window.screen.height;

  const url = "../frontend/exam_page.php/new-window?takeExamBtn=" + btn_val;
  const windowName = "Exam Page";
  const windowFeatures = "width=" + sWidth + ", height=" + sHeight;

  window.open(url, windowName, windowFeatures);
}

function hide_scheduled(btnType) {
  if (btnType.id == "hideScheduledExamsBtn") {
    var x = document.getElementById("scheduledExams");

    if (x.style.display != "none") {
      x.style.display = "none";
      btnType.innerHTML = "Show Scheduled Exams";
      btnType.style.background = "green";
    } else {
      x.style.display = "block";
      btnType.innerHTML = "Hide Scheduled Exams";
      btnType.style.background = "red";
    }
  }

  if (btnType.id == "hideResultsBtn") {
    var x = document.getElementById("examResults");

    if (x.style.display != "none") {
      x.style.display = "none";
      btnType.innerHTML = "Show Results";
      btnType.style.background = "green";
    } else {
      x.style.display = "block";
      btnType.innerHTML = "Hide Results";
      btnType.style.background = "red";
    }
  }
}

window.onload = getStudentBasicDetails();
window.onload = () => {
  fetch("../backend/verify_student_session.php")
    .then((resp1) => {
      return resp1.text();
    })
    .then((resp2) => {
      if (resp2 == "outOfSession") {
        window.location.href = "../frontend/login_student.php";
      }
    });
};
