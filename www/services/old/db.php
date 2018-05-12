<?php
//open connection to mysql db
try {
    $bdd = new PDO("mysql:host=siidfelekdmbe.mysql.db;dbname=siidfelekdmbe", "siidfelekdmbe", "ldFd4GD6");
    $bdd->exec("SET CHARACTER SET utf8");
}catch(Exception $e){
    die ("une erreur est survenue : ".$e->getMessage());
}

function checkUser($id){
    global $bdd;
    
    $reponse = $bdd->prepare('SELECT * FROM users WHERE user_id = :user_id');
    $reponse->execute(['user_id' => $id]);
    $data = $reponse->fetchAll();
    
    if($data > 0){
		return true;
	}
}

function userPoints($id){
    global $bdd;
	
    $reponse = $bdd->prepare('SELECT user_id, twitter_sub, facebook_sub, instagram_sub FROM usa WHERE user_id = :user_id AND administrative_area_level_1 = :administrative_area_level_1');
    $reponse->execute([
		'user_id' => $id,
		'administrative_area_level_1' => 'NY'
	]);
	
	// $reponse = $bdd->prepare('SELECT user_id, twitter_sub, facebook_sub, instagram_sub FROM paris WHERE user_id = :user_id');
    // $reponse->execute([
		// 'user_id' => $id
	// ]);
	
    $data = $reponse->fetchAll();
	
	return $data;
}

function everySubs(){
    global $bdd;
	
    $reponse = $bdd->prepare('SELECT user_id, twitter_sub, facebook_sub, instagram_sub FROM usa WHERE administrative_area_level_1 = :administrative_area_level_1');
    $reponse->execute(['administrative_area_level_1' => 'NY']);
	
    // $reponse = $bdd->prepare('SELECT user_id, twitter_sub, facebook_sub, instagram_sub FROM paris');
    // $reponse->execute();
	
    $data = $reponse->fetchAll();
	
	$total = [];
	
	for($i=0;$i<sizeof($data);$i++){
		$total[] = $data[$i]['facebook_sub'] + $data[$i]['twitter_sub'] + $data[$i]['instagram_sub'];
	}
	
	return max($total);
}

function addFacebookSubs($id, $subs){
	global $bdd;
	
	$reponse = $bdd->prepare('UPDATE usa SET facebook_sub = :facebook_sub WHERE user_id = :user_id');
	$reponse->execute([
		'user_id' => $id,
		'facebook_sub' => $subs
	]);

	return true;
}

function addTwitterSubs($id, $subs){
	global $bdd;
	
	// $reponse = $bdd->prepare('UPDATE usa SET twitter_sub = :twitter_sub WHERE user_id = :user_id');
	$reponse = $bdd->prepare('UPDATE paris SET twitter_sub = :twitter_sub WHERE user_id = :user_id');
	$reponse->execute([
		'user_id' => $id,
		'twitter_sub' => $subs
	]);

	return true;
}

function addInstagramSubs($id, $subs){
	global $bdd;
	
	// $reponse = $bdd->prepare('UPDATE usa SET instagram_sub = :instagram_sub WHERE user_id = :user_id');
	$reponse = $bdd->prepare('UPDATE paris SET instagram_sub = :instagram_sub WHERE user_id = :user_id');
	$reponse->execute([
		'user_id' => $id,
		'instagram_sub' => $subs
	]);

	return true;
}


function updatePoints($id, $points){
	global $bdd;
	
	// $reponse = $bdd->prepare('UPDATE usa SET points = :points WHERE user_id = :user_id');
	$reponse = $bdd->prepare('UPDATE paris SET points = :points WHERE user_id = :user_id');
	$reponse->execute([
		'user_id' => $id,
		'points' => $points
	]);

	return true;
}

function checkUpVote($user_id, $artist_id){
	global $bdd;
	
	$reponse = $bdd->prepare('SELECT score_user_id, score_artist_id FROM scores WHERE score_user_id = :score_user_id AND score_artist_id = :score_artist_id');
	$reponse = execute([
		'score_user_id' => $user_id,
		'score_artist_id' => $artist_id
	]);
	
	$donnees = $reponse->fetchAll();
	
	if(sizeof($donnees > 0){
		return false;
	}else{
		return true;
	}
}

function upVote($user_id, $artist_id){
	global $bdd;
	
	$reponse = $bdd->prepare('INSERT INTO scores(score_user_id, score_artist_id, score_datetime) VALUES(:score_user_id, :score_artist_id, :score_datetime)');
	$reponse->execute([
		'score_user_id' => $user_id,
		'score_artist_id' => $artist_id,
		'score_datetime' => date('Y-m-d H:i:s')
	]);
	
	return true;
}