window.addEventListener("load", () => {
  var item = document.querySelectorAll(".item");
  var slider = document.querySelector(".slider");
  var next = document.getElementById("next");
  var prev = document.getElementById("prev");
  var autoplay = document.getElementById("autoplay");
  var screenWidth = window.innerWidth;

  if (screenWidth >= 1200) {
    var width = 800;
  }
  else if (screenWidth >= 992 && screenWidth < 1200) {
    var width = 800;
  }
  else if (screenWidth >= 768 && screenWidth < 992) {
    var width = 500;
  }
  else if (screenWidth >= 576 && screenWidth < 768) {
    var width = 300;
  }
  else if (screenWidth < 576) {
    var width = 300;
  }

  next.addEventListener("click", function () {
    slider.scrollLeft += width;
    setTimeout(() => {
      this.setAttribute("disabled", "true");
    }, 0);
    setTimeout(() => {
      this.removeAttribute("disabled", "true");
    }, 1000);
  });
  prev.addEventListener("click", function () {
    slider.scrollLeft -= width;
    setTimeout(() => {
      this.setAttribute("disabled", "true");
    }, 0);
    setTimeout(() => {
      this.removeAttribute("disabled", "true");
    }, 1000);
  });

});

