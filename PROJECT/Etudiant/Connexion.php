<?php

    try{
        $bdd = new PDO('mysql:host=localhost;dbname=fstage',"root","",array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );

    }catch(PDOException $e){
        echo "Connexion echoué".$e->getMessage();
    }


?>