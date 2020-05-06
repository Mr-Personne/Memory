window.addEventListener("load", () => {
    // console.log("home script");

    const headerLogo = document.querySelectorAll(".title-text span");
    const brainLogo = document.querySelector(".brain-logo");

    headerLogo.forEach(element => {
        element.addEventListener("mouseover", (e) => {
            // console.log("hover ", e);
            // const brainImg = e.target.childNodes[0].childNodes[1];
            // console.log("brainImg ", brainImg);
            brainLogo.classList.add("wiggle-me");
        });
        element.addEventListener("mouseout", (e) => {
            // console.log("hover ", e);
            // const brainImg = e.target.childNodes[0].childNodes[1];
            // console.log("brainImg ", brainImg);
            brainLogo.classList.remove("wiggle-me");
        });
    });

    brainLogo.addEventListener("mouseover", (e) => {
        // console.log("hover ", e);
        const brainImg = e.target;
        // console.log("brainImg ", brainImg);
        brainImg.classList.add("wiggle-me");
    });
    brainLogo.addEventListener("mouseout", (e) => {
        // console.log("hover ", e);
        const brainImg = e.target;
        // console.log("brainImg ", brainImg);
        brainImg.classList.remove("wiggle-me");
    });

    const mainButtons = document.querySelectorAll(".main-button");

    if (mainButtons != null) {
        mainButtons.forEach(element => {
            element.addEventListener("mouseover", (e) => {
                if (e.target.tagName == "IMG") {
                    var mainButtonImg = e.target;
                    // console.log("e.target ", e.target);
                }
                else if (e.target.tagName == "P") {
                    var mainButtonImg = e.target.childNodes[0];
                }
                else {
                    var mainButtonImg = e.target.childNodes[1].childNodes[0];
                    // console.log("not e.target ", e.target);
                }
                mainButtonImg.classList.add("shake-me");
            });

            element.addEventListener("mouseout", (e) => {
                if (e.target.tagName == "IMG") {
                    var mainButtonImg = e.target;
                }
                else if (e.target.tagName == "P") {
                    var mainButtonImg = e.target.childNodes[0];
                }
                else {
                    var mainButtonImg = e.target.childNodes[1].childNodes[0];
                }
                mainButtonImg.classList.remove("shake-me");
            });
        });
    }

});