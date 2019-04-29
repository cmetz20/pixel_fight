/*
Christopher Metz
CSC 436 - Cloud Computing
Project 3
Pixel Fight!
*/
let colorSelected = "black";
let currentUpdateID = -1;
window.onload = function () {
    sendBoardRequest();
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
    currentUpdateID = getIntitialUpdateID();
}

async function sendBoardRequest(){
    try {
        const resp = await fetch("./pixelfight-api.php?board-request");
        var data = await resp.json();
        let buttons = document.getElementsByTagName("button");
        for(let i = 0; i < data.length; i++){
            if(buttons[i].className !== data[i]){
                buttons[i].className = data[i];
            }
        }
        setInterval(gridChangeCheck, 500);
    } catch (err) {
        console.log(err);
    }
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
            sendBoardRequest()
        );
}

async function getIntitialUpdateID(){
    try {
        const resp = await fetch("./pixelfight-api.php?update-id");
        var data = await resp.json();
        currentUpdateID = data.body;
        document.getElementById("board-version").innerHTML = currentUpdateID;
        setInterval(gridChangeCheck, 1000);
    } catch (err) {
        console.log(err);
    }
}

// function is called every second, checking last ID
// for grid update. Only has to check an INT
// rather than reupdating all the time for no reason.
async function gridChangeCheck() {
    if(currentUpdateID != -1){
        try {
            const resp = await fetch("./pixelfight-api.php?update-id");
            var data = await resp.json();
            if(data.type === "update-id"){
                if(data.body === currentUpdateID){
                    console.log("No updates to DB");
                }
                else{
                    sendBoardRequest();
                }
            }
        } catch (err) {
            console.log(err);
        }
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