<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("connectDB.php");
require("validateCredentials.php");

$user = $_POST['user'];

$sql = "SELECT COUNT(*) FROM users WHERE BINARY user=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 's', $user);
    mysqli_stmt_bind_result($statement, $count);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_fetch($statement);
        if($count != 0){
            echo "Nome utente già usato!";
            exit();
        }
        mysqli_stmt_close($statement);
    }
}

$pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);

$sql = "INSERT INTO users(user,pass) VALUES(?,?)";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 'ss', $user, $pass);
    if(mysqli_stmt_execute($statement)){
        $sql = "INSERT INTO stats(user) VALUES(?)";
        if($statement = mysqli_prepare($connect, $sql)){
            mysqli_stmt_bind_param($statement, 's', $user);
            if(mysqli_stmt_execute($statement)){
                echo "success";
                exit();
            }
        }
    }
}

echo "Qualcosa è andato storto!";

?>