<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$artists = artists();

// var_dump($artists);

$length = sizeof($artists);

for($i=0;$i<$length;$i++){
	insertPoints('', $artists[$i]["user_id"], $artists[$i]["points"]);
}