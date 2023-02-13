<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("connectDB.php");

if((!isset($_SESSION['logged']) || $_SESSION['logged'] == false || !isset($_SESSION['username'])) == false){   
    $sql = "DELETE FROM pending WHERE BINARY user=?";
    if($statement = mysqli_prepare($connect, $sql)){
        mysqli_stmt_bind_param($statement, 's', $_SESSION['username']);
        mysqli_stmt_execute($statement);
    }   
}

?>