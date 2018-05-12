<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

// $reponse = $bdd->query('SELECT user_id, facebook FROM usa WHERE administrative_area_level_1 = "NY"');
$reponse = $bdd->query('SELECT user_id, facebook FROM usa');

while ($donnees = $reponse->fetch()) 
{
	$id_facebook= str_replace('https://www.facebook.com/', '',$donnees['facebook']);

	// echo '<p>' .$donnees['user_id']. '</p>' ;

	$access_token= '1571389909831886|aK8JhbMSaQMrwlt-m8k-75LnqOQ';
	$data = file_get_contents('https://graph.facebook.com/'. $id_facebook. '/?fields=fan_count&access_token=' . $access_token);
	$json = json_decode($data, true);

	// echo($json['fan_count']);
	
	addFacebookSubs($donnees['user_id'], $json['fan_count']);
}