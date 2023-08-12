function getSettings() {
  if (document.getElementById("changeBtn")) {
    document
      .getElementById("container")
      .removeChild(document.getElementById("changeBtn"));
  }

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

      console.log(r2.admin);

      if (adminType == true) {
        var button = document.createElement("button");
        button.setAttribute("onclick", "changeSetting()");
        button.id = "changeBtn";

        if (adminAgreement) {
          button.innerHTML = "Disable - Enabled";
        } else {
          button.innerHTML = "Enable - Disabled";
        }

        document.getElementById("container").appendChild(button);
      } else {
        return;
      }
    });
}

function changeSetting() {
  var formdata = new FormData();
  formdata.append("requestFor", "changeAgreement");

  fetch("../backend/adminDevSettings.php", {
    method: "POST",
    body: formdata,
  })
    .then((r1) => {
      return r1.json();
    })
    .then((r2) => {
      if (r2.error == "none" && r2.status == true) {
        getSettings();
      } else {
        console.log(r2.error);
      }
    });
}

window.onload = getSettings();
