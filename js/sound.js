// SOUND VARIABLES
let pawnSound;
let muted;

function initSound(){
    pawnSound = new Audio("audio/pawn.ogg");
    pawnSound.load();
    muted = localStorage.getItem("muted");
    if(muted == undefined)
        muted = false;
    else if(muted == "true")
        muted = true;
    else if(muted == "false")
        muted = false;
    if(muted)
        soundButton.src = "img/muted.svg";
}

function updateSound(){
    muted = !muted;
    localStorage.setItem("muted", muted);
    if(muted)
        soundButton.src = "img/muted.svg";
    else
        soundButton.src = "img/sound.svg";
}

function playSound(){
    if(!muted){
        if(typeof pawnSound.currentTime != "undefined")
            pawnSound.currentTime = 0;
        pawnSound.play();
    }
  }