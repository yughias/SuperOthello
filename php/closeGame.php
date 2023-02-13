<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("gameEngine.php");
require("connectDB.php");

if(!isset($_SESSION["logged"]) || $_SESSION["logged"] == false){
    exit();
}


$sql = "SELECT board FROM games WHERE BINARY white=? OR BINARY black=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 'ss', $_SESSION['username'], $_SESSION['username']);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_bind_result($statement, $board);
        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);
        if(!endGame($board))
            exit();
    } else
        exit();
} else
    exit();


$sql = "SELECT gameover FROM games WHERE BINARY white=? OR BINARY black=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 'ss', $_SESSION['username'], $_SESSION['username']);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_bind_result($statement, $gameover);
        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);
        if($gameover == 1){
            $sql = "DELETE FROM games WHERE BINARY white=? OR BINARY black=?";
            if($statement = mysqli_prepare($connect, $sql)){
                mysqli_stmt_bind_param($statement, 'ss', $_SESSION['username'], $_SESSION['username']);
                mysqli_stmt_execute($statement);
            }
        } else {
            $sql = "UPDATE games SET gameover=1 WHERE BINARY white=? OR BINARY black=?";
            if($statement = mysqli_prepare($connect, $sql)){
                mysqli_stmt_bind_param($statement, 'ss', $_SESSION['username'], $_SESSION['username']);
                mysqli_stmt_execute($statement);
            }
        }
    }
}

?>