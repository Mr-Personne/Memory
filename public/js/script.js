console.log("i'm here");
// function timer(minutes, secondes) {

//     if (secondes === 0) {
//         minutes = minutes - 1;
//         secondes = 60;
//     }
//     secondes = secondes - 1;

//     //stringy minutes for extra zero in front
//     if (minutes < 10) {
//         var stringyMins = "0" + minutes;
//     }
//     else {
//         var stringyMins = minutes;
//     }

//     //stringy secondes for extra zero in front
//     if (secondes < 10) {
//         var stringySecs = "0" + secondes;
//     }
//     else {
//         var stringySecs = secondes;
//     }


//     var minDisplay = document.querySelector(".minutes-display");
//     minDisplay.innerText = stringyMins;

//     var secDisplay = document.querySelector(".secondes-display");
//     secDisplay.innerText = stringySecs;

//     var currentFullTimeInSecondes = (minutes * 60) + secondes;
//     var progressPercent = (currentFullTimeInSecondes * 100) / fullTimeInSecondes;
//     // console.log(progressPercent);
//     var progressBar = document.querySelector(".progress-bar");
//     progressBar.style.width = progressPercent + "%";


//     timeout = setTimeout(timer, 1000);
//     if (minutes === 0 && secondes === 0) {
//         // alert("Time over!");
//         clearTimeout(timeout);
//         /*onOff=0;*/
//     }
// }


//ajax
function ajaxCallAsynch(section) {
    // console.dir(myForm);
    console.log('subProject : ', subProject);

    console.log(section);
    var formData = new FormData();
    if (section === undefined) {
        console.log("end ajax default ", section);
        return;
    }
    else {
        console.log("coucou ajax");
    }



    fetch(`${section}/memorise/end`).then(function (response) {

        console.dir(response);
        return response.text();

    }).then(function (data) {
        console.log("data : ", data);


    });


}

  //END AJAX!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  //END AJAX!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  //END AJAX!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!