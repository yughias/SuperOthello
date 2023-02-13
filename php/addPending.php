<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("connectDB.php");

if(!isset($_SESSION['logged']) || $_SESSION['logged'] == false || !isset($_SESSION['username'])){
    echo "error in pending";
    exit();
}
 
$sql = "SELECT COUNT(*) FROM games WHERE BINARY black=? OR BINARY white=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 'ss', $_SESSION['username'], $_SESSION['username']);
    mysqli_stmt_bind_result($statement, $count);
    mysqli_stmt_execute($statement);
    mysqli_stmt_fetch($statement);
    mysqli_stmt_close($statement);
    if($count != 0){ 
        echo "success!";    
        exit();
    }
    
}

$sql = "SELECT COUNT(user) FROM pending WHERE BINARY user!=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 's', $_SESSION['username']);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_bind_result($statement, $count);
        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);
        if($count == 0){
            $sql = "INSERT INTO pending values(?)";
            if($statement = mysqli_prepare($connect, $sql)){
                mysqli_stmt_bind_param($statement, 's', $_SESSION['username']);
                mysqli_stmt_execute($statement);
            }
        } else {
            $sql = "SELECT * FROM pending LIMIT 1";
            if($statement = mysqli_prepare($connect, $sql)){
                mysqli_stmt_execute($statement);
                mysqli_stmt_bind_result($statement, $user);
                mysqli_stmt_fetch($statement);
                mysqli_stmt_close($statement);
                $sql = "DELETE FROM pending WHERE BINARY user=?";
                if($statement = mysqli_prepare($connect, $sql)){
                    mysqli_stmt_bind_param($statement, 's', $user);
                    mysqli_stmt_execute($statement);
                    mysqli_stmt_close($statement);
                    $sql = "INSERT INTO games(black, white, timer) VALUES(?, ?, ?)";
                    if($statement = mysqli_prepare($connect, $sql)){
                        $timer = TIMER;
                        mysqli_stmt_bind_param($statement, 'sss', $user, $_SESSION['username'], $timer);
                        mysqli_stmt_execute($statement);
                        mysqli_stmt_close($statement);
                    }
                }
            }
        }
    }
}

echo "end pending";

?>