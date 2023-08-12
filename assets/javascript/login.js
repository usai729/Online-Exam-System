function getSettings() {
  var formdata = new FormData();
  formdata.append("requestFor", "info");

  fetch("../backend/adminDevSettings.php", {
    method: "POST",
    body: formdata,
  })
    .then((r1) => {
      return r1.json();
    })
    .then((r2) => {
      var adminType = r2.type;
      var adminAgreement = r2.agreement;

      if (!adminAgreement) {
        document.getElementById("form").removeAttribute("action");
      } else {
        document
          .getElementById("form")
          .setAttribute("action", "../backend/login.php");
      }
    });
}

window.onload = getSettings();
