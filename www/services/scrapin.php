<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$reponse = $bdd->query('SELECT user_id, instagram FROM usa WHERE administrative_area_level_1 = "NY" LIMIT 2');

while($donnees = $reponse->fetch()){

	$new = str_replace('https://instagram.com/', '',$donnees['instagram']);

	$data = file_get_contents('https://api.instagram.com/v1/users/'.$new.'/follows/?client_id=d61ddb0c10c7437b9eb50aae9db0f98c'); 
	$parsed =  json_decode($data,true);
	// $tw_followers =  $parsed[0]['followers_count'];
	
	var_dump($parsed);
	
	
	// var_dump($donnees['user_id'], $tw_followers);
	// addInstagramSubs($donnees['user_id'], $tw_followers);
}