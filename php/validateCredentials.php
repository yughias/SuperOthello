<?php

if(isset($_POST['user']) && isset($_POST['pass'])){
    if(!preg_match("/^\w{1,8}$/", $_POST['user']) || !preg_match("/^\w{8,}$/", $_POST['pass'])){
        echo "Campi inseriti non validi!";
        exit();
    }
}

?>