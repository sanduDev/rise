<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");

require_once 'db.php';

$user_id = filter_input(INPUT_GET, 'user_id');
$artist_id = filter_input(INPUT_GET, 'artist_id');

if($user_id && $artist_id){
	// then, we check if user has upvoted that artist
	$checkUpVote = checkUpVote($user_id, $artist_id);
	
	if($checkUpVote == NULL){
		echo 'not_upvoted';
	}else{
		echo 'upvoted';
	}
}