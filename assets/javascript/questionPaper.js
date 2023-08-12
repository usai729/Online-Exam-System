function holdID() {
  var x = document.getElementById("eCode");
}

function showFields(number) {
  var x = document.getElementById("options");
  var questions = number.value;

  x.innerHTML = "";

  var array = Array();

  for (let i = 1; i <= questions; i++) {
    var temp_arr = Array();

    var div = document.createElement("div");
    div.id = i;
    div.style.margin = "5px";
    var label = document.createElement("label");
    label.innerHTML = "Question " + i;
    var input = document.createElement("select");
    input.name = "question_" + i;
    input.style.marginLeft = "5px";

    for (let j = 1; j <= 4; j++) {
      var opt = document.createElement("option");
      opt.value = "Opt " + j;
      opt.innerHTML = "Opt " + j;

      input.appendChild(opt);
    }

    div.appendChild(label);
    div.appendChild(input);

    array.push(div);
  }

  array.forEach((element) => {
    document.getElementById("options").appendChild(element);
  });

  var submitBtn = document.createElement("button");
  submitBtn.id = "submit_forms";
  submitBtn.innerHTML = "Submit";
  submitBtn.setAttribute("onclick", "submitForms()");

  document.getElementById("options").appendChild(submitBtn);
}

function setToNum(startNumField) {
  var startNum = startNumField.value;
  var endNum = document.getElementById("endNum");
  var totalQuestions = document.getElementById("noOfQuestions");

  var endNumVal = parseInt(startNum) + parseInt(totalQuestions.value) - 1;

  endNum.value = endNumVal;
}

function negativeMarking() {
  var x = document.getElementById("negativeMarkingBtn");

  if (x.checked != true) {
    document.getElementById("negativeMarking").style.display = "none";
  } else {
    document.getElementById("negativeMarking").style.display = "block";
  }
}

window.onload = () => {
  document.getElementById("negativeMarkingBtn").checked = true;
};
window.onload = negativeMarking();

function checkNegative(x) {
  if (x.value >= 0) {
    x.value = -1;
  }
}

function submitForms() {
  var settings = document.getElementById("settings");
  var options = document.getElementById("options");
  document.getElementById("submit_forms").value = true;

  var formdata = new FormData();

  var settingsObj = {};
  for (var pair of new FormData(settings).entries()) {
    settingsObj[pair[0]] = pair[1];
  }
  var optionsObj = {};
  for (var pair of new FormData(options).entries()) {
    optionsObj[pair[0]] = pair[1];
  }

  formdata.append("settings", JSON.stringify(settingsObj));
  formdata.append("options", JSON.stringify(optionsObj));

  fetch("../backend/addOptions.php", {
    method: "POST",
    body: formdata,
  })
    .then((resp1) => {
      return resp1.text();
    })
    .then((resp2) => {
      if (resp2 == "done") {
        window.location.href =
          "http://localhost:8000/frontend/scheduleExam.php";
      }

      console.log(resp2);
    })
    .catch((e) => {
      return;
    });
}
