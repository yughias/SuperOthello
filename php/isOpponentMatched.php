<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("connectDB.php");

if(!isset($_SESSION["logged"]) || $_SESSION["logged"] == false){
    echo "false";
    exit();
}

$sql = "SELECT COUNT(*) FROM games WHERE BINARY white=? OR BINARY black=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 'ss', $_SESSION['username'], $_SESSION['username']);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_bind_result($statement, $count);
        mysqli_stmt_fetch($statement);
        if($count != 0){
            echo "true";
            exit();
        }
    }
}

echo "false";

?>