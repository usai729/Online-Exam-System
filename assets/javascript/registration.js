function getSettings() {
  var formdata = new FormData();

  formdata.append("requestFor", "registrationSettingAdmin");

  fetch("../backend/adminDevSettings.php", {
    method: "post",
    body: formdata,
  })
    .then((r1) => {
      return r1.json();
    })
    .then((r2) => {
      var managingAdmin = r2.managingAdmin;
      var regPermit = r2.regPermit;

      if (!regPermit) {
        document.getElementById("container").style.display = "none";
        document.getElementById("registrationForm").removeAttribute("action");
      } else {
        document.getElementById("container").style.display = "block";
        document
          .getElementById("registrationForm")
          .setAttribute("action", "../backend/register.php");
      }
    });
}

getSettings();
