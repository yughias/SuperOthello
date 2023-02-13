<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("gameEngine.php");
require("connectDB.php");


$sql = "SELECT black, white, board FROM games WHERE BINARY white=? OR BINARY black=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 'ss', $_SESSION['username'], $_SESSION['username']);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_bind_result($statement, $data['black'], $data['white'], $data['board']);
        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);
    }
}

if(!isset($data['black']) || !isset($data['white']) || !isset($data['board']))
    exit();

if(!endGame($data['board']))
    exit();

$count = getCount($data['board']);

$field = "online_draw";
if($data['black'] == $_SESSION['username'] && $count['black'] > $count['white'])
    $field = "online_win";
if($data['white'] == $_SESSION['username'] && $count['white'] > $count['black'])
    $field = "online_win";
if($data['black'] == $_SESSION['username'] && $count['black'] < $count['white'])
    $field = "online_loss";
if($data['white'] == $_SESSION['username'] && $count['white'] < $count['black'])
    $field = "online_loss";

$sql = "UPDATE stats SET X=X+1 WHERE BINARY user=?";
$sql = str_replace('X', $field, $sql);

if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 's', $_SESSION['username']);
    mysqli_stmt_execute($statement);
}


?>