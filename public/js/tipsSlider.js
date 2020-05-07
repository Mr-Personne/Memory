var item = document.querySelectorAll(".item");
var slider = document.querySelector(".slider");
var next = document.getElementById("next");
var prev = document.getElementById("prev");
var autoplay = document.getElementById("autoplay");

next.addEventListener("click", function () {
  slider.scrollLeft += 600;
  setTimeout(() => {
    this.setAttribute("disabled", "true");
  }, 0);
  setTimeout(() => {
    this.removeAttribute("disabled", "true");
  }, 1000);
});
prev.addEventListener("click", function () {
  slider.scrollLeft -= 600;
  setTimeout(() => {
    this.setAttribute("disabled", "true");
  }, 0);
  setTimeout(() => {
    this.removeAttribute("disabled", "true");
  }, 1000);
});
