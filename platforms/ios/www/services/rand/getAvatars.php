<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 

// open connection to mysql db
try {
    $bdd = new PDO("mysql:host=siidfelekdmbe.mysql.db;dbname=siidfelekdmbe", "siidfelekdmbe", "ldFd4GD6");
    // $bdd = new PDO("mysql:host=localhost;dbname=rise", "root", "");
	$bdd->exec("SET CHARACTER SET utf8");
}
catch(Exception $e)
{
	die ("une erreur est survenue : ".$e->getMessage());
}

function updateaccount($avatar_url, $soundcloud){
	global $bdd;
	
	$reponse = $bdd->prepare('UPDATE usa SET avatar_url = :avatar_url WHERE soundcloud = :soundcloud');
	$reponse->execute([
		'avatar_url' => $avatar_url,
		'soundcloud' => $soundcloud
	]);
	
	return true;
}


// fetch table rows from mysql db
$reponse = $bdd->query('SELECT * FROM usa WHERE soundcloud != null');

while ($donnees = $reponse->fetch())
{
	$sc = $donnees['soundcloud'];
	$sn = str_replace('https://soundcloud.com/','',$sc);
	
	$url = "http://api.soundcloud.com/users/$sn?client_id=5fd371b0c0d2c54f6de733c69c25105b";

	// call api
	$json = file_get_contents($url);
	$json = json_decode($json, true);
	

	$userImage = str_replace('https://i1.sndcdn.com/', '',$json['avatar_url']); // renaming image
	
	if(updateaccount($userImage, $donnees['soundcloud'])){
		$profile_Image = $donnees['avatar_url']; //image url
		$path = './rise/';  // your saving path
		$thumb_image = file_get_contents($profile_Image);
		if ($http_response_header != NULL) {
			$thumb_file = $path . $userImage;
			file_put_contents($thumb_file, $thumb_image);
		}
	}
}
$reponse->closeCursor();