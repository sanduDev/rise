<?php

// ce script a permis de transferer les points déjà inscrits dans la table artists dans la table scores
// n'est utilisé qu'une fois

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$artists = artists();

// var_dump($artists);

$length = sizeof($artists);

for($i=0;$i<$length;$i++){
	insertPoints('', $artists[$i]["user_id"], $artists[$i]["points"]);
}