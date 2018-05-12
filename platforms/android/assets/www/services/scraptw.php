<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$reponse = $bdd->query('SELECT user_id, twitter FROM usa WHERE user_id > 1000 limit 500');
// $reponse = $bdd->query('SELECT user_id, twitter FROM usa WHERE administrative_area_level_1 = "NY"');

while($donnees = $reponse->fetch()){

	$new = str_replace('https://twitter.com/', '',$donnees['twitter']);

	$data = file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names='.$new); 
	$parsed =  json_decode($data,true);
	$tw_followers =  $parsed[0]['followers_count'];
	
	
	// var_dump($donnees['user_id'], $tw_followers);
	addTwitterSubs($donnees['user_id'], $tw_followers);
}