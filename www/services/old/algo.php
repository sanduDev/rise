<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

require_once 'db.php';

$reponse = $bdd->query('SELECT user_id, twitter FROM usa WHERE administrative_area_level_1 = "NY"');
// $reponse = $bdd->query('SELECT user_id FROM paris');


// ici on récupère le plus grand nombre de fans (via une autre fonction qui est dans le fichier bdd.php)
// il est en dehors de la boucle parce que c'est une valeur fixe (oui, y'a qu'un meilleur) du coup pas besoin de répéter cette valeur
$higher_social = everySubs();

while($donnees = $reponse->fetch()){

	// on récupère l'id de l'artiste pour pouvoir le selectionner dans la bdd
	$id = $donnees['user_id'];
	
	// on récupère ses scores des réseaux sociaux
	$facebook_sub = userPoints($id)[0]['facebook_sub'];
	$twitter_sub = userPoints($id)[0]['twitter_sub'];
	$instagram_sub = userPoints($id)[0]['instagram_sub'];

	// on fait la moyenne
	$average_social_network = $facebook_sub + $twitter_sub + $instagram_sub;
	
	// le fameus algorithme
	// la fonction "round" permet d'arrondir la valeur
	$points = round(($average_social_network / $higher_social) * 100000);
	
	// une fois qu'on a tous ce qu'il faut, on mets à jours les scores dans la bdd
	updatePoints($donnees['user_id'], $points);
	
	// c'est bon !
}