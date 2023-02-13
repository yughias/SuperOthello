const INTERVAL_MS = 200;
const ERR_MESSAGE = "Connessione interrotta!";

async function isLogged(){
    let response = await fetch("php/isLogged.php", {method: "GET"})
    .then((response) => { return response.text(); })
    .catch(() => { alertWrite(ERR_MESSAGE, "red"); return "not logged"; });
    if(response == "logged")
        return true;
    else
        return false;
}

async function phpRegister(username, password){
    let url = 'php/register.php';
    let formData = new FormData();
    formData.append('user', username);
    formData.append('pass', password);

    let result = await fetch(url, { method: 'POST', body: formData })
    .then((response) => { return response.text(); })
    .catch(() => {return ERR_MESSAGE});
    return result;
}

async function phpLogin(username, password){
    let url = 'php/login.php';
    let formData = new FormData();
    formData.append('user', username);
    formData.append('pass', password);

    let result = await fetch(url, { method: 'POST', body: formData })
    .then((response) => { return response.text(); })
    .catch(() => {return ERR_MESSAGE});
    return result;
}

function phpGetStats(){
    let url = 'php/getStats.php';
    let result = fetch(url, {method: 'GET'})
    .then((response) => { return response.json(); })
    .catch(() => { return {error: true} });
    return result;
}

async function phpLogout(){
    let url = 'php/logout.php';
    fetch(url, {method: 'GET'})
    .catch(() => {return ERR_MESSAGE});
}

function phpUpdateCpuStats(stats){
    let url = 'php/updateCpuStats.php';
    let formData = new FormData();
    formData.append('stats', stats);
    fetch(url, { method: 'POST', body: formData })
}

function phpUpdateOnlineStats(){
    let url = 'php/updateOnlineStats.php';
    fetch(url, { method: 'GET'});
}

function phpAddPending(){
    let url = 'php/addPending.php';
    fetch(url, { method: 'GET'})
    .then((response) => response.text())
    .then((response) => {
        if(response == "connessione al database non riuscita.")
            setTimeout(phpAddPending, INTERVAL_MS);
    })
    .catch(() => {setTimeout(phpAddPending, INTERVAL_MS)});
}

function phpOpponentMatched(){
    let response = fetch("php/isOpponentMatched.php", {method: "GET"})
    .then((response) => { return response.text(); })
    .then((response) => { return response == "true" ? true : false;})
    .catch(() => {return false});
    return response;
}

async function phpGetGameData(){
    let errorOccured = false;
    let result = await fetch("php/getGameData.php", {method: "GET"})
    .catch(() => { 
        errorOccured = true;
        setTimeout(phpGetGameData, INTERVAL_MS);
        return {error: true}; })
    .then((result) => result.json())
    .catch(() => {
        let json = {error: true};
        return json;
    })
    waitConnection = errorOccured;
    if(!result.error){
        let board_string = await result.board.split('');
        result.board = parseBoardFromPhp(board_string);
        result.currentPlayer = result.currentPlayer == "black" ? BLACK : WHITE;
    }
    else {
        result.timer = timerDOM.textContent;
        result.board = board;
        result.currentPlayer = currentPlayer;
        result.white = whiteNameDOM.textContent;
        result.black = blackNameDOM.textContent;
        result.wait = waitOpponent;
    }
    return result;
}

function phpSendMove(move){
    let url = 'php/sendMove.php';
    let formData = new FormData();
    formData.append('x', move[0]);
    formData.append('y', move[1]);
    let result = fetch(url, { method: 'POST', body: formData})
    .catch(() => {setTimeout(phpSendMove(move), INTERVAL_MS)})
    .then((response) => response.text())
    .then((response) => {
        if(response == "connessione al database non riuscita.")
            setTimeout(phpSendMove(move), INTERVAL_MS);
    })
    return result;
}

function phpCloseGame(){
    let url = 'php/closeGame.php';
    fetch(url, { method: 'GET'})
    .catch(() => {setTimeout(phpCloseGame, INTERVAL_MS)})
    .then((response) => response.text())
    .then((response) => {
        if(response == "connessione al database non riuscita.")
            setTimeout(phpCloseGame, INTERVAL_MS);
    });
}