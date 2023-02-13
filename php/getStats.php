<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("connectDB.php");

$json = array('error' => false);

if(!isset($_SESSION['logged']) || $_SESSION['logged'] == false || !isset($_SESSION['username'])){
    $json['error'] = true;
    exit();
}

$sql = "SELECT * FROM stats WHERE BINARY user=?";
        if($statement = mysqli_prepare($connect, $sql)){
            mysqli_stmt_bind_param($statement, 's', $_SESSION['username']);
            if(mysqli_stmt_execute($statement)){
                mysqli_stmt_bind_result(
                    $statement,
                    $json['user'],
                    $json['cpu_win'],
                    $json['cpu_loss'],
                    $json['online_win'],
                    $json['online_loss'],
                    $json['online_draw'],
                    $json['cpu_draw']
                );
                mysqli_stmt_fetch($statement);
            } else
                $json['error'] = true;
        }

echo json_encode($json);
?>