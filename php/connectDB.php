<?php

define("TIMER", 60);
define("HOST", "localhost");
define("DATABASE", "game_db");        

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try{
    $connect = mysqli_connect(HOST, "root", "", DATABASE);
    if(mysqli_connect_errno()){
        die(mysqli_connect_error());
    }
} catch(mysqli_sql_exception $e){
    echo "connessione al database non riuscita.";
    exit();
}

?>