<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("connectDB.php");
require("gameEngine.php");

if(!isset($_POST['x']) || !isset($_POST['y']))
    exit();

if(!isset($_SESSION['logged']) || $_SESSION['logged'] == false)
    exit();


$sql = "SELECT black, white, board, currentPlayer, gameover FROM games WHERE BINARY white=? OR BINARY black=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 'ss', $_SESSION['username'], $_SESSION['username']);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_bind_result($statement, $data['black'], $data['white'], $data['board'], $data['currentPlayer'], $data['gameover']);
        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);
    }
}

$_POST['x'] = (int)$_POST['x'];
$_POST['y'] = (int)$_POST['y'];
$data['currentPlayer'] = $data['currentPlayer'] == "black" ? "B" : "W";
$data['nextPlayer'] = $data['currentPlayer'] == "B" ? "white" : "black";

//default timer value
$timer = TIMER;

if(!isset($data['board'])){
    echo "game doesn't exists or already closed!";
    exit();
}

if(isPossibleMove($data['board'], $_POST['x'], $_POST['y'], $data['currentPlayer'])){
    $data['board'] = reverse($data['board'], $_POST['x'], $_POST['y'], $data['currentPlayer']);
    if(noValidMove($data['board'], $data['nextPlayer'])){
        $data['nextPlayer'] = $data['nextPlayer'] == "black" ? "white" : "black";
    }
    $sql = "UPDATE games SET board=?, currentPlayer=?, timer=? WHERE BINARY black=? OR BINARY white=?";
    if($statement = mysqli_prepare($connect, $sql)){
        mysqli_stmt_bind_param($statement, 'sssss', $data['board'], $data['nextPlayer'], $timer, $_SESSION['username'], $_SESSION['username']);
        mysqli_stmt_execute($statement);
    }
}

echo "success!";

?>