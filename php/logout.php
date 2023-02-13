<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

if(isset($_SESSION['logged']) && $_SESSION['logged']){
    $_SESSION['logged'] = false;
    $_SESSION['username'] = '';
    echo "unlogged";
} else 
    echo "error";

?>