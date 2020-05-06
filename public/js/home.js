window.addEventListener("load", () => {
    console.log("home script");

    const mainButtons = document.querySelectorAll(".main-button");

    mainButtons.forEach(element => {
        element.addEventListener("mouseover", (e) => {
            console.log("hover ", e);
            const mainButtonImg = e.target.childNodes[1].childNodes[0];
            // console.log("mainButtonImg ", mainButtonImg);
            mainButtonImg.classList.add("bounce-me");
        });

        element.addEventListener("mouseout", (e) => {
            // console.log("hover ", e);
            const mainButtonImg = e.target.childNodes[1].childNodes[0];
            // console.log("mainButtonImg ", mainButtonImg);
            mainButtonImg.classList.remove("bounce-me");
        });
    });
});