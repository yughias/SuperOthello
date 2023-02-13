<?php 

if(session_status() == PHP_SESSION_NONE)
    session_start();

if(isset($_SESSION["logged"]) && $_SESSION["logged"] == true)
    echo "logged";
else
    echo "not logged";

?>