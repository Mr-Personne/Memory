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


  //generate dots
  //set dotsAmount and DotActiveList based on tips html page items...
  const dotsAmount = 6;
  var dotActiveList = ["active", "dot", "dot", "dot", "dot", "dot"]
  const dotSection = document.querySelector(".dot-section");
  var dotSpans = '<span id="dot" class="dot active"></span>';

  for (let i = 2; i <= dotsAmount; i++) {
    dotSpans += '<span id="dot" class="dot"></span>';
  }
  dotSection.innerHTML = dotSpans;

  //set on click dots
  const dots = document.querySelectorAll(".dot");
  var currPageNum = 1;
  // console.log(dots);
  for (let j = 0; j < dot.length; j++) {
    // console.log(dots[j]);
    // dots[j].addEventListener('click', slideToPage(j+1, width));
    
    //ADD EVENT DOTS
    dots[j].addEventListener('click', () => {
      
      var num = j+1;
      // console.log("dots inner " , num);
      
      //LEFT to right
      if (currPageNum < num) {
        // console.log("currPageNum ", currPageNum, "");
        // console.log(width+ " * " + " ( "+num+ " - "+ currPageNum+" )");
        var currwidth = width * (num - currPageNum);
        // console.log("currwidth " , currwidth,"width " , width);
        // dots[currPageNum-1].classList.remove("active");
        // dotActiveList[currPageNum-1] = "dot";
        // dots[num-1].classList.add("active");
        // dotActiveList[num-1] = "active";

        var aIndx = dotActiveList.indexOf("active");
        // dotActiveList = ["dot", "dot", "dot", "dot"];
        dotActiveList[aIndx] = "dot";
        dotActiveList[num-1] = "active";
        dots[aIndx].classList.remove("active");
        dots[num-1].classList.add("active");

        slider.scrollLeft += currwidth;
        setTimeout(() => {
          // this.setAttribute("disabled", "true");
        }, 0);
        setTimeout(() => {
          // this.removeAttribute("disabled", "true");
        }, 1000);
        currPageNum = num;
      }
      else if(currPageNum > num){
        //RIGHT TO LEFT
        // console.log("currPageNum ", currPageNum, "");
        // console.log(width+ " * " + " ( "+currPageNum+ " - "+ num+" )");
        var currwidth = width * (currPageNum - num);
        // console.log("currwidth " , currwidth,"width " , width);
        // dots[currPageNum-1].classList.remove("active");
        // dotActiveList[currPageNum-1] = "dot";
        // dots[num-1].classList.add("active");
        // dotActiveList[num-1] = "active";
        
        var aIndx = dotActiveList.indexOf("active");
        // dotActiveList = ["dot", "dot", "dot", "dot"];
        dotActiveList[aIndx] = "dot";
        dotActiveList[num-1] = "active";
        dots[aIndx].classList.remove("active");
        dots[num-1].classList.add("active");

        slider.scrollLeft -= currwidth;


        setTimeout(() => {
          // this.setAttribute("disabled", "true");
        }, 0);
        setTimeout(() => {
          // this.removeAttribute("disabled", "true");
        }, 1000);
        currPageNum = num;
      }
        
        
    });
  }

  next.addEventListener("click", function () {
    slider.scrollLeft += width;
    if (dotActiveList.indexOf("active") != -1 && dotActiveList.indexOf("active") != dotActiveList.length-1) {
      var aIndx = dotActiveList.indexOf("active");
      // console.log("aIndx ", aIndx, "");
      dotActiveList[aIndx] = "dot";
      dotActiveList[aIndx+1] = "active";
      dots[aIndx].classList.remove("active");
      dots[aIndx+1].classList.add("active");
      var curraIndx = dotActiveList.indexOf("active");
      currPageNum = curraIndx+1;
      // console.log("currPageNum ", currPageNum, "");
    }
    setTimeout(() => {
      this.setAttribute("disabled", "true");
    }, 0);
    setTimeout(() => {
      this.removeAttribute("disabled", "true");
    }, 1000);
  });
  prev.addEventListener("click", function () {
    slider.scrollLeft -= width;
    if (dotActiveList.indexOf("active") != -1 && dotActiveList.indexOf("active") != 0) {
      var aIndx = dotActiveList.indexOf("active");
      dotActiveList[aIndx] = "dot";
      dotActiveList[aIndx-1] = "active";
      dots[aIndx].classList.remove("active");
      dots[aIndx-1].classList.add("active");
      var curraIndx = dotActiveList.indexOf("active");
      currPageNum = curraIndx+1;
      // console.log("curraIndx ", curraIndx, "");
      // console.log("currPageNum ", currPageNum, "");
    }
    setTimeout(() => {
      this.setAttribute("disabled", "true");
    }, 0);
    setTimeout(() => {
      this.removeAttribute("disabled", "true");
    }, 1000);
  });
});

