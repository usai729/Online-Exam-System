function submitOMR() {
  var form = document.getElementById("exam-form");
  var formdata = new FormData(form);

  fetch("http://localhost:8000/backend/evaluation.php", {
    method: "POST",
    body: formdata,
  })
    .then((resp1) => {
      return resp1.text();
    })
    .then((resp2) => {
      if (resp2 == "ok") {
        window.close();
      } else {
        console.log(resp2);
      }
    })
    .catch((e) => {
      return;
    });
}

function clearSelection(button) {
  var optionName = button.value;
  var radios = document.getElementsByName(optionName);

  for (var i = 0; i < radios.length; i++) {
    radios[i].checked = false;
  }
}

document.addEventListener("contextmenu", (e) => {
  e.preventDefault();
});

window.onload = () => {
  document.getElementById("file_link").click();
};
