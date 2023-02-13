function checkForOpponent(){
    phpOpponentMatched()
    .then((result) => {
        if(result){
            disableLoadingScreen();
            gameScenario.style.display = "flex";
            initGame(ONLINE_MODE);
        } else 
            setTimeout(checkForOpponent, INTERVAL_MS);
    });
}

function parseBoardFromPhp(board_string){
    for(let i = 0; i < board_string.length; i++){
        board_string[i] = board_string[i].replace('B', BLACK);
        board_string[i] = board_string[i].replace('W', WHITE);
        board_string[i] = board_string[i].replace('X', EMPTY);
    }

    let board = [];
    for(let y = 0; y < BOARD_SIZE; y++)
        board.push([]);

    for(let y = 0; y < BOARD_SIZE; y++)    
        for(let x = 0; x < BOARD_SIZE; x++)
            board[x][y] = board_string[x+y*BOARD_SIZE];
    
    return board;
}

async function waitForOpponentMove(){
    gameData = await phpGetGameData();
    whiteNameDOM.textContent = gameData.white;
    blackNameDOM.textContent = gameData.black;
    currentPlayer = gameData.currentPlayer;
    waitOpponent = gameData.wait;
    lastMove = calculateLastMove(lastMove, board, gameData.board);
    board = gameData.board;
    timerDOM.textContent = gameData.timer;

    if(gameData.timer == 0){
        gameOver = true;
        disableTimerAnimation();
        createHomeReturnButton();
    }
    
    showCurrentPlayer(currentPlayer);
    updateScore();

    if(!waitOpponent && gameData.timer > 0){
        playSound();
        timerHandler = setTimeout(decreaseTimer, INTERVAL_MS);
    }

    if(endGame(board)){
        gameOver = true;
        nextMove();   
    }

    if(waitOpponent && gameData.timer > 0 && !gameOver) 
        setTimeout(waitForOpponentMove, INTERVAL_MS);
}

function isEmpty(board){
    for(let row of board)
        for(let cell of row)
            if(cell != EMPTY)
                return false;
    return true;
}

function calculateLastMove(last_move, old_board, new_board){
    if(isEmpty(old_board))
        return last_move;
    for(let y = 0; y < BOARD_SIZE; y++)
        for(let x = 0; x < BOARD_SIZE; x++)
            if(old_board[x][y] == EMPTY && new_board[x][y] != EMPTY)
                return [x, y];
    return last_move;
}

async function decreaseTimer(){
    gameData = await phpGetGameData();
    timerDOM.textContent = gameData.timer;
    
    if(gameData.timer > 0 && !gameOver){
        timerHandler = setTimeout(decreaseTimer, INTERVAL_MS);
    } else {
        disableTimerAnimation();
        createHomeReturnButton();
        gameOver = true;
    }
}

function disableTimerAnimation(){
    let timerImg = document.getElementById("timerImg");
    timerImg.style.animationIterationCount = 0;
}