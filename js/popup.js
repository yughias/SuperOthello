// SETTINGS POPUP ITEMS
let settingsPopup;
let difficultySlider;
let difficultyName;
let playBtn;

// ACCESS POPUP ITEMS
let accessPopup;
let registerBtn;
let loginBtn;

// USER STARS POPUP ITEMS
let userStatsPopup;
let logoutBtn;
let loginAlertHandler;

// LOGIN AND REGISTER CONSTANT
const MIN_PASSWORD_LENGTH = 8;
const MAX_USERNAME_LENGTH = 8;

function initPopup(){
    closingButtons = document.getElementsByClassName("closeButton");
    for(let btn of closingButtons)
        btn.addEventListener("click", closePopup);
    settingsPopup = document.getElementById("settingsPopup");
    difficultySlider = document.getElementById("difficultySlider");
    difficultyName = document.getElementById("difficultyName");
    playBtn = document.getElementById("playButton");
    difficultySlider.addEventListener("input", changeDifficulty);
    playBtn.addEventListener("click", startCpuGame);

    accessPopup = document.getElementById("accessPopup");
    registerBtn = document.getElementById("registerButton");
    loginBtn = document.getElementById("loginButton");
    registerBtn.addEventListener("click", () => login(true));
    loginBtn.addEventListener("click", () => login(false));

    userStatsPopup = document.getElementById("userStatsPopup");
    logoutBtn = document.getElementById("logoutButton");
    logoutBtn.addEventListener("click", logout);

    let userField = document.getElementById("username");
    let passField = document.getElementById("password");
    userField.addEventListener("input", (event) => {
            event.target.setCustomValidity('');
            if(!event.target.checkValidity()){
                if(event.target.value.length > MAX_USERNAME_LENGTH)
                    event.target.setCustomValidity("Sono concessi massimo " + MAX_USERNAME_LENGTH + " caratteri.");
                else
                    event.target.setCustomValidity("Sono ammessi solo caratteri alfanumerici.");
            }
            event.target.reportValidity();
    });

    passField.addEventListener("input", (event) => {
        event.target.setCustomValidity('');
        if(!event.target.checkValidity()){
            if(event.target.value.length < MIN_PASSWORD_LENGTH)
                event.target.setCustomValidity("La password deve contenere almeno " + MIN_PASSWORD_LENGTH + " caratteri.");
            else
                event.target.setCustomValidity("Sono ammessi solo caratteri alfanumerici.");
        }
        event.target.reportValidity();
    });
}

function openPopup(popup){
    if((gameScenario.style.display != "none" && (gameMode == ONLINE_MODE || gameMode == CPU_MODE)) || loadingText.style.display == "flex")
        logoutBtn.disabled = true;

    popup.style.display = "block";
}

function closePopup(){
    let buttons = document.getElementsByTagName("button");
    for(let button of buttons)
        if(button.id != "onlineButton")
            button.disabled = false;

    // empty login/register fields
    let fields = document.getElementsByTagName("input");
    for(let field of fields)
        field.value = "";

    let popups = document.getElementsByClassName("overlay");
    for(let popup of popups)
        popup.style.display = "none";

    let alert = document.getElementById("alert");
    alert.style.display = "none";
}

function changeDifficulty(){
    let value = Number(difficultySlider.value);
    MAX_DEPTH = value;
    if(value == 1)
        difficultyName.textContent = "Facile";
    if(value == 2)
        difficultyName.textContent = "Normale";
    if(value == 3)
        difficultyName.textContent = "Difficile";
    if(value == 4)
        difficultyName.textContent = "Esperto";
    if(value == 5)
        difficultyName.textContent = "Impossibile";
}

async function handleLoginIcon(){
    if(await isLogged()) {
        updateStats();
        openPopup(userStatsPopup);
    } else
        openPopup(accessPopup);
}

async function adjustIcon(){
    if(await isLogged()){
        loginIcon.src = "img/user.svg";
        loginIcon.title = "statistiche";
    } else {
        loginIcon.src = "img/login.svg";
        loginIcon.title = "login";
    }
}

async function login(from_new_user){
    let username = document.getElementById("username");
    let password = document.getElementById("password");

    if(username.value == '' || password.value == '' || !username.checkValidity() || !password.checkValidity()){
        alertWrite("Campi inseriti non validi!", "red");
        return;
    }

    let phpResult = from_new_user ? await phpRegister(username.value, password.value) : await phpLogin(username.value, password.value); 

    if(phpResult == "success"){
        alertWrite("Login avvenuto con successo!", "green");
        if(from_new_user)
            await phpLogin(username.value, password.value);
        loginBtn.disabled = true;
        onlineBtn.disabled = false;
        onlineBtn.title = "";
        registerBtn.disabled = true;
        navUsername.textContent = username.value;
        adjustIcon();
        setTimeout(closePopup, 2500);
    } else {
        alertWrite(phpResult, "red");
    }
}

function alertWrite(string, color){
    let alert = document.getElementById("alert");
    alert.style.animationName = "";
    alert.style.display = "none";

    setTimeout(() => {
        alert.textContent = string;
        alert.style.display = "block"
        alert.style.backgroundColor = color;
        alert.style.animationName = "alertAnimation";
    }, 10);

    if(loginAlertHandler)
        clearTimeout(loginAlertHandler);

    loginAlertHandler = setTimeout(() => {
        alert.style.animationName = "";
        alert.style.display = "none";
    }, 2500);
}

function updateStats(){
    phpGetStats()
    .then((json) => {
        if(json.error)
            return;

        let statsTable = document.getElementById("statsTable");
        let caption = document.getElementById("statsCaption");
        caption.textContent = "Statistiche di " + json.user;

        let online_total = json.online_draw + json.online_loss + json.online_win;
        let online_ratio = online_total == 0 ? 0 : json.online_win / online_total;

        let cpu_total = json.cpu_draw + json.cpu_loss + json.cpu_win;
        let cpu_ratio = cpu_total == 0 ? 0 : json.cpu_win / cpu_total;

        let WIDTH = 5;
        let tds = statsTable.getElementsByTagName("td");
        tds[1].textContent = json.online_win;
        tds[2].textContent = json.online_loss;
        tds[3].textContent = json.online_draw;
        tds[4].textContent = Number.parseInt(online_ratio*100) + "%";
        
        tds[1+WIDTH].textContent = json.cpu_win;
        tds[2+WIDTH].textContent = json.cpu_loss;
        tds[3+WIDTH].textContent = json.cpu_draw;
        tds[4+WIDTH].textContent = Number.parseInt(cpu_ratio*100) + "%";
    });
}

async function logout(){
    logoutBtn.disabled = true;
    onlineBtn.disabled = true;
    onlineBtn.title = "devi essere loggato per giocare online";
    await phpLogout();
    navUsername.textContent = "";
    setTimeout(adjustIcon, 100);
    setTimeout(closePopup, 400);
}

function disableLoadingScreen(){
    loadingBall.style.display = 'none';
    loadingText.style.display = 'none';
}

function enableLoadingScreen(){
    loadingBall.style.display = 'flex';
    loadingText.style.display = 'flex';
}