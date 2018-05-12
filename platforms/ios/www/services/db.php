<?php

//open connection to mysql db
try {
    $bdd = new PDO("mysql:host=siidfelekdmbe.mysql.db;dbname=siidfelekdmbe", "siidfelekdmbe", "ldFd4GD6");
    $bdd->exec("SET CHARACTER SET utf8");
}catch(Exception $e){
    die ("une erreur est survenue : ".$e->getMessage());
}

require_once 'functions.php';