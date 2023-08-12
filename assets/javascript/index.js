document.getElementById("linkA").addEventListener("mouseover", () => {
  document.getElementById("header").style.background =
    "linear-gradient(90, rgb(35, 61, 61), #ddd)";
});

document.getElementById("linkB").addEventListener("mouseover", () => {
  document.getElementById("header").style.background =
    "linear-gradient(90, #ddd, rgb(35, 61, 61))";
});
