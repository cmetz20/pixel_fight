/*
Christopher Metz
CSC 436 - Cloud Computing
Project 2
Pixel Fight!
*/
let colorSelected = "black";
window.onload = function () {
    for (let i = 0; i < 10; i++) {
        for (let j = 0; j < 10; j++) {
            document.getElementById("btn-" + i + "-" + j).onclick = btnClick;
        }
    }
    // all buttons are assigned click listener
    let cookieColor = document.cookie.split(";");
    cookieColor = cookieColor[cookieColor.length - 1].trim();
    // select previously selected color by grabbing it from cookies
    if (cookieColor != "") {
        console.log(cookieColor);
        // user has already set a color in radio buttons, re-select that color
        document.getElementById("black").checked = false;
        document.getElementById(cookieColor).checked = true;
        setColor(cookieColor);
    }

    setTimeout(gridChangeCheck, 1000);
}

// function is called when any button is clicked,
// creates a POST message and sends it to the PHP
// to appropriately update database
// function then reloads page to show updated DB
function btnClick(event) {
    // this.id = current btn and also point val in database
    let message = this.id + getColorSelection();
    console.log(message);
    fetch("./pixelfight.php", {
            method: "POST",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: message
        })
        .then(
            setTimeout(function () {
                window.location.reload(true)
            }, 1000)
        );

}

// function is called every second, checking last ID
// for grid update. Only has to check an INT
// rather than reupdating all the time for no reason.
function gridChangeCheck() {
    let reload = false;

    if (reload) {
        window.location.reload(true);
    }
}

// creates a string with the selected color for POST
function getColorSelection() {
    return "_" + colorSelected;
}

// sets global variable colorSelected
// also adds the value to the end of the cookie var
function setColor(value) {
    document.cookie = value;
    colorSelected = value;
}