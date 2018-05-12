<?php

function getUser($id){
	global $bdd;
	
	$reponse = $bdd->prepare('SELECT * FROM usa WHERE user_id = :user_id');
	$reponse->execute(['user_id' => $id]);
	$data = $reponse->fetchAll(PDO::FETCH_ASSOC);
	
	return $data;
}

function getUserRank($points, $location, $location_value){
	global $bdd;
	
	// $reponse = $bdd->prepare("SELECT user_id, surname, postal_code, locality, administrative_area_level_2, administrative_area_level_1, country, points FROM paris WHERE points >= :points AND $location = :position");
	
	$reponse = $bdd->prepare("SELECT user_id, surname, locality, administrative_area_level_2, administrative_area_level_1, country, points FROM usa WHERE points >= :points AND $location = :position");
	
	$reponse->execute([
		'points' => $points,
		'position' => $location_value
	]);
	$data = $reponse->fetchAll();
	
	$data = sizeof($data);
	
	return $data;
}


function getUsersByLocation($location, $location_value){
	global $bdd;
	
	$reponse = $bdd->prepare("SELECT user_id, surname, locality, administrative_area_level_2, administrative_area_level_1, country FROM usa WHERE $location = :position");
	
	$reponse->execute([
		'position' => $location_value
	]);
	$data = $reponse->fetchAll(PDO::FETCH_ASSOC);
	
	return $data;
}

function subscribe($arr){
    global $bdd;
    
	$reponse = $bdd->prepare('INSERT INTO users(facebook_id, user_surname, user_email, user_avatar, sub_datetime) VALUES(:facebook_id, :name, :email, :picture, :sub_datetime)');
	$reponse->execute([
		'facebook_id' => $arr['facebook_id'],
		'name' => $arr['name'],
		'email' => $arr['email'],
		'picture' => $arr['picture'],
		'sub_datetime' => date('Y-m-d H:i:s')
	]);
    
	return true;
}

function checkUser($id){
    global $bdd;
    
    $reponse = $bdd->prepare('SELECT facebook_id FROM users WHERE facebook_id = :facebook_id');
    $reponse->execute([
		'facebook_id' => $id
	]);
	
    $data = $reponse->rowCount();
    
    if($data > 0){
		return true;
	}else{
		return false;
	}
}

function artists(){
    global $bdd;
    
    $reponse = $bdd->prepare('SELECT * FROM usa');
    $reponse->execute();
	
    $data = $reponse->fetchAll();
    
	return $data;
}

function users(){
    global $bdd;
    
    $reponse = $bdd->prepare('SELECT * FROM users');
    $reponse->execute();
	
    $data = $reponse->fetchAll();
    
	return $data;
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
	
	$reponse = $bdd->prepare('UPDATE usa SET twitter_sub = :twitter_sub WHERE user_id = :user_id');
	// $reponse = $bdd->prepare('UPDATE paris SET twitter_sub = :twitter_sub WHERE user_id = :user_id');
	$reponse->execute([
		'user_id' => $id,
		'twitter_sub' => $subs
	]);

	return true;
}

function addInstagramSubs($id, $subs){
	global $bdd;
	
	$reponse = $bdd->prepare('UPDATE usa SET instagram_sub = :instagram_sub WHERE user_id = :user_id');
	// $reponse = $bdd->prepare('UPDATE paris SET instagram_sub = :instagram_sub WHERE user_id = :user_id');
	$reponse->execute([
		'user_id' => $id,
		'instagram_sub' => $subs
	]);

	return true;
}

function showPoints($id){
	global $bdd;
	
	$reponse = $bdd->query('SELECT SUM(scores_value) AS total FROM scores WHERE scores_artist_id = '.$id.'');
	$donnees = $reponse->fetchAll();
	
	return $donnees['0']['total'];
}

function showPointsC($id){
	global $bdd;
	
	$reponse = $bdd->query('SELECT SUM(scores_value) AS total FROM scores WHERE scores_artist_id = '.$id.' AND scores_user_id != 0');
	$donnees = $reponse->fetchAll();
	
	return $donnees['0']['total'];
}

function insertPoints($user_id, $artist_id, $value){
	global $bdd;
	
	$reponse = $bdd->prepare('INSERT INTO scores(scores_user_id, scores_artist_id, scores_value, scores_datetime) VALUES(:scores_user_id, :scores_artist_id, :scores_value, :scores_datetime)');
	$reponse->execute([
		'scores_user_id' => $user_id,
		'scores_artist_id' => $artist_id,
		'scores_value' => $value,
		'scores_datetime' => date('Y-m-d H:i:s')
	]);
	
	return true;
}

function updatePoints($id, $points){
	global $bdd;
	
	$reponse = $bdd->prepare('UPDATE usa SET points = :points WHERE user_id = :user_id');
	// $reponse = $bdd->prepare('UPDATE paris SET points = :points WHERE user_id = :user_id');
	$reponse->execute([
		'user_id' => $id,
		'points' => $points
	]);

	return true;
}

function checkUpVote($user_id, $artist_id){
	global $bdd;
	
	$reponse = $bdd->query('SELECT * FROM scores WHERE scores_user_id = '.$user_id.' AND scores_artist_id = '.$artist_id.'');
	
    $donnees = $reponse->fetchAll();
	
	return $donnees;
}

function upVote($user_id, $artist_id){
	global $bdd;
	
	$reponse = $bdd->prepare('INSERT INTO scores(scores_user_id, scores_artist_id, scores_value, scores_datetime) VALUES(:scores_user_id, :scores_artist_id, :scores_value, :scores_datetime)');
	$reponse->execute([
		'scores_user_id' => $user_id,
		'scores_artist_id' => $artist_id,
		'scores_value' => 1,
		'scores_datetime' => date('Y-m-d H:i:s')
	]);
	
	return true;
}

function remVote($user_id, $artist_id){
	global $bdd;
	
	$reponse = $bdd->prepare('DELETE FROM scores WHERE scores_user_id = :scores_user_id AND scores_artist_id = :scores_artist_id');
	$reponse->execute([
		'scores_user_id' => $user_id,
		'scores_artist_id' => $artist_id
	]);
	
	return true;
}

function checkTrack($user_id, $track_id){
	$reponse = $bdd->query('SELECT * FROM favorites_tracks WHERE favorite_track_user_id = :favorite_track_user_id AND 	favorite_track_src = :favorite_track_src');
	$reponse = execute([
		'favorite_track_user_id' = $user_id,
		'	favorite_track_src' = $track_id,
	])
	$donnees = $reponse->fetchAll();
	
	if(sizeof($donnees)>0){
		return true;
	}else{
		return false;
	}
}

function addFavTrack($user_id, $favorite_track_artist_id, $favorite_track_artist_surname, $favorite_track_permalink_url, $favorite_track_src, $favorite_track_title, $favorite_track_artwork){
	$reponse = $bdd->prepare('INSERT INTO favorites_tracks(favorite_track_user_id,favorite_track_artist_id,favorite_track_artist_surname,favorite_track_permalink_url,favorite_track_src,favorite_track_title,favorite_track_artwork,favorite_track_datetime) VALUES(:favorite_track_user_id,:favorite_track_artist_id,:favorite_track_artist_surname,:favorite_track_permalink_url,:favorite_track_src,:favorite_track_title,:favorite_track_artwork,:favorite_track_datetime)');
	$reponse->execute([
		'favorite_track_user_id' => $user_id,
		'favorite_track_artist_id' => $favorite_track_artist_id,
		'favorite_track_artist_surname' => $favorite_track_artist_surname,
		'favorite_track_permalink_url' => $favorite_track_permalink_url,
		'favorite_track_src' => $favorite_track_src,
		'favorite_track_title' => $favorite_track_title,
		'favorite_track_artwork' => $favorite_track_artwork,
		'favorite_track_datetime' => date('Y-m-d H:i:s')
	]);

	return true;
}