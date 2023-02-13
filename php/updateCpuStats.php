<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("connectDB.php");

$sql = "UPDATE stats SET X=X+1 WHERE BINARY user=?";
$sql = str_replace('X', $_POST['stats'], $sql);

if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 's', $_SESSION['username']);
    mysqli_stmt_execute($statement);
}


?>