// OFTEN USED DOM ELEMENTS 
let body;
let menu;
let gameScenario;

// MAIN MENU BUTTONS
let cpuBtn;
let localBtn;
let onlineBtn;
let aboutBtn;

// NAV BAR ELEMENTS
let loginIcon;
let navUsername;
let soundButton;

// LOADING ANIMATION ELEMENTS
let loadingBall;
let loadingText;

function init(){
    menu = document.getElementById("menu");
    cpuBtn = document.getElementById("cpuButton");
    localBtn = document.getElementById("localButton");
    onlineBtn = document.getElementById("onlineButton");
    aboutBtn = document.getElementById("aboutButton");
    aboutBtn.addEventListener("click", () => window.location.href = "about.html");
    initPopup();
    localBtn.addEventListener("click", startLocalGame);
    cpuBtn.addEventListener("click", () => openPopup(settingsPopup));
    gameScenario = document.getElementById("gameScenario");
    body = document.getElementsByTagName("body")[0];
    loginIcon = document.getElementById("loginIcon");
    loginIcon.addEventListener("click", handleLoginIcon);
    navUsername = document.getElementById("nav-username");
    soundButton = document.getElementById("soundButton");
    soundButton.addEventListener("click", updateSound);
    onlineBtn.addEventListener("click", startOnlineGame);
    initSound();
    loadingBall = document.getElementById("loadingBall");
    loadingText = document.getElementById("loadingText");
}

function startLocalGame(){
    createGameScenario();
    initGame(LOCAL_MODE);
}

function startCpuGame(){
    createGameScenario();
    initGame(CPU_MODE);
    closePopup();
}

function startOnlineGame(){
    phpAddPending();
    createGameScenario();
    gameScenario.style.display = "none";
    enableLoadingScreen();
    checkForOpponent();
}

function createGameScenario(){
    menu.remove();
    cpuBtn.remove();
    localBtn.remove();
    onlineBtn.remove();
    aboutBtn.remove();
    gameScenario.style.display = "flex";
}