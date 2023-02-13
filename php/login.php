<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("connectDB.php");
require("validateCredentials.php");

$user = $_POST['user'];
$pass = $_POST['pass'];

$sql = "SELECT pass FROM users WHERE BINARY user=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 's', $user);
    mysqli_stmt_bind_result($statement, $hash);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_fetch($statement);
        if(password_verify($pass, $hash)){
            $_SESSION["logged"] = true;
            $_SESSION["username"] = $user;
            echo "success";
            exit();
        }
    } 
}

echo "Nome utente o password non validi!";
?>