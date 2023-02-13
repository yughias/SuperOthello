<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

require("connectDB.php");

$json['error'] = true;

if(!isset($_SESSION["logged"]) || $_SESSION["logged"] == false){
    echo json_encode($json);
    exit();
}

$sql = "SELECT * FROM games WHERE BINARY white=? OR BINARY black=?";
if($statement = mysqli_prepare($connect, $sql)){
    mysqli_stmt_bind_param($statement, 'ss', $_SESSION['username'], $_SESSION['username']);
    if(mysqli_stmt_execute($statement)){
        mysqli_stmt_bind_result($statement,
            $json['black'],
            $json['white'],
            $json['board'],
            $json['currentPlayer'],
            $json['gameover'],
            $json['timer']
        );
        mysqli_stmt_fetch($statement);
        if(!isset($json['currentPlayer'])){
            echo json_encode($json);
            exit();
        }
        if($_SESSION['username'] == $json[$json['currentPlayer']])
            $json['wait'] = false;
        else
            $json['wait'] = true;
        if($json['timer'] < 0)
            $json['timer'] = 0;
        $json['error'] = false;
    }
}

echo json_encode($json);

?>