<?php

define("BOARD_SIZE", 8);

function isPossibleMove($board, $x, $y, $currentPlayer) {
  if($x < 0 || $x >= BOARD_SIZE || $y < 0 || $y >= BOARD_SIZE || $board[$x+$y*BOARD_SIZE] != 'X')
    return false;

  $opponent = $currentPlayer == 'B' ? 'W' : 'B';

  for($dx = -1; $dx <= 1; $dx++) {
    for($dy = -1; $dy <= 1; $dy++) {
      if($dx == 0 && $dy == 0)
        continue;
      $i = $x + $dx;
      $j = $y + $dy;
      if($i < 0 || $i >= BOARD_SIZE || $j < 0 || $j >= BOARD_SIZE || $board[$i+$j*BOARD_SIZE] != $opponent)
        continue;
      while($i >= 0 && $i < BOARD_SIZE && $j >= 0 && $j < BOARD_SIZE && $board[$i+$j*BOARD_SIZE] == $opponent){
        $i = $i + $dx;
        $j = $j + $dy;
      }
      if($i >= 0 && $i < BOARD_SIZE && $j >= 0 && $j < BOARD_SIZE && $board[$i+$j*BOARD_SIZE] == $currentPlayer)
        return true;
    }
  }

  return false;
}

function reverse($board, $x, $y, $player) {
  $opponent = $player == 'B' ? 'W' : 'B';
  $board[$x+$y*BOARD_SIZE] = $player;

  for($dx = -1; $dx <= 1; $dx++) {
    for($dy = -1; $dy <= 1; $dy++) {
        if($dx == 0 && $dy == 0)
          continue;
        $i = $x + $dx;
        $j = $y + $dy;
        $pawns = [];
        if($i < 0 || $i >= BOARD_SIZE || $j < 0 || $j >= BOARD_SIZE || $board[$i+$j*BOARD_SIZE] != $opponent)
          continue;
        while($i >= 0 && $i < BOARD_SIZE && $j >= 0 && $j < BOARD_SIZE && $board[$i+$j*BOARD_SIZE] == $opponent) {
          $pawns[] = [$i, $j];
          $i = $i + $dx;
          $j = $j + $dy;
        }
        if($i >= 0 && $i < BOARD_SIZE && $j >= 0 && $j < BOARD_SIZE && $board[$i+$j*BOARD_SIZE] == $player)
          foreach($pawns as $key => $pawn) {
            $board[$pawn[0]+$pawn[1]*BOARD_SIZE] = $player;
          }
    }
  }

  return $board;
}

function noValidMove($board, $player){
  $player = $player == 'black' ? 'B' : 'W'; 
  for($y = 0; $y < BOARD_SIZE; $y++)
    for($x = 0; $x < BOARD_SIZE; $x++)
      if(isPossibleMove($board, $x, $y, $player))
        return false;
  return true;
}

function getCount($board){
  $data['black'] = 0;
  $data['white'] = 0;
  for($y = 0; $y < BOARD_SIZE; $y++)
    for($x = 0; $x < BOARD_SIZE; $x++)
      if($board[$x+$y*BOARD_SIZE] == 'B')
        $data['black']++;
      else if($board[$x+$y*BOARD_SIZE] == 'W')
        $data['white']++;
  return $data;
}

function endGame($board){
  for($y = 0; $y < BOARD_SIZE; $y++)
    for($x = 0; $x < BOARD_SIZE; $x++)
      if(isPossibleMove($board, $x, $y, 'W') || isPossibleMove($board, $x, $y, 'B'))
        return false;
  return true;
}

?>