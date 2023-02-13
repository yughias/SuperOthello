let MAX_DEPTH = 2;

let heuristic = [100, -30, 10,  5,  5, 10, -30, 100, 
                 -30, -50, -2, -2, -2, -2, -50, -30, 
                  10,  -2,  5, -1, -1,  5,  -2,  10, 
                   5,  -2, -1,  2,  2, -1,  -2,   5,
                   5,  -2, -1,  2,  2, -1,  -2,   5,
                  10,  -2,  5, -1, -1,  5,  -2,  10,
                 -30, -50, -2, -2, -2, -2, -50, -30,
                 100, -30, 10,  5,  5, 10, -30, 100];

function cpuMove(){
  let bestMove = [undefined, undefined];
  let bestScore = -Number.MAX_VALUE;
  let score;
  for(let y = 0; y < BOARD_SIZE; y++){
      for(let x = 0; x < BOARD_SIZE; x++){
          if(isValidMove(x, y, BLACK, board)){
              let clone = reverse(x, y, BLACK, board);
              score = minimax(clone, 0, false, -Number.MAX_VALUE, +Number.MAX_VALUE);
              if(score > bestScore){
                  bestScore = score;
                  bestMove[0] = x;
                  bestMove[1] = y;
              }
          }
      }
  }
  if(bestMove[0] == undefined || bestMove[1] == undefined)
    return;
  if(isValidMove(bestMove[0], bestMove[1], BLACK, board)){
    playSound();
    board = reverse(bestMove[0], bestMove[1], BLACK, board);
    lastMove = bestMove;
  }
}

// pseudocode at https://en.wikipedia.org/wiki/Alpha%E2%80%93beta_pruning
// alpha is the actual max score for the AI
// beta is the actual min score for the player
function minimax(board, depth, isMaximizing, alpha, beta){
  let breaked = false;
  let result = calculateScore(board);
  if(depth >= MAX_DEPTH) return result;
  if(isMaximizing && PossibleMoves(BLACK, board) == false) return result;
    else if(PossibleMoves(WHITE, board) == false) return result;

  if(isMaximizing) {
    let value = -Number.MAX_VALUE;
    for(let y = 0; y < BOARD_SIZE; y++) {
      for(let x = 0; x < BOARD_SIZE; x++) {
        // is the spot available?
        if (isValidMove(x, y, BLACK, board)) {
          let clone = reverse(x, y, BLACK, board);
          value = Math.max(value, minimax(clone, depth + 1, false, alpha, beta));
          alpha = Math.max(alpha, value);
          if(value >= beta) { breaked = true; break; }
        }
      }
      if(breaked) break;
    }
    return value;
  } else {
    let value = Number.MAX_VALUE;
    for (let y = 0; y < BOARD_SIZE; y++) {
      for (let x = 0; x < BOARD_SIZE; x++) {
        // Is the spot available?
        if (isValidMove(x, y, WHITE, board)) {
          let clone = reverse(x, y, WHITE, board);
          value = Math.min(value, minimax(clone, depth + 1, true, alpha, beta));
          beta = Math.min(beta, value);
          if(value <= alpha) { breaked = true; break; }
        }
      }
      if(breaked) break;
    }
    return value;
  }
}

function PossibleMoves(player, board){
  for(let y = 0; y < BOARD_SIZE; y++){
    for(let x = 0; x < BOARD_SIZE; x++){
      if(isValidMove(x, y, player, board))
        return true;
    }
  }
  return false;
}

function calculateScore(board){
  let count = 0;
  for(let x = 0; x < BOARD_SIZE; x++){
    for(let y = 0; y < BOARD_SIZE; y++){
      count += board[x][y]*(MAX_DEPTH <= 2 ? 1 : heuristic[x + y*BOARD_SIZE]);
    }
  }
  return count;
}
  