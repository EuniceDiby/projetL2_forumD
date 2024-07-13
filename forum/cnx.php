<?php
    $server="localhost";
    $users="root";
    $mdp="";
    $db="forum";

    $link = mysqli_connect($server, $users, $mdp,$db);

    if($link){
        echo ""; 
    }else{
        die(mysqli_connect_error());
    }
?>